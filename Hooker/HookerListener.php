<?php

namespace Statamic\Addons\Hooker;

use GuzzleHttp\Client;
use Statamic\Extend\Listener;
use GuzzleHttp\RequestOptions;
use Statamic\Events\Data\PageMoved;
use Statamic\Events\Data\PageSaved;
use Statamic\Events\Data\RoleSaved;
use Statamic\Events\Data\TermSaved;
use Statamic\Events\Data\UserSaved;
use Statamic\Events\Data\AssetMoved;
use Statamic\Events\Data\EntrySaved;
use Statamic\Events\Data\PagesMoved;
use Statamic\Events\Data\PageDeleted;
use Statamic\Events\Data\RoleDeleted;
use Statamic\Events\Data\TermDeleted;
use Statamic\Events\Data\UserDeleted;
use Statamic\Events\Data\AssetDeleted;
use Statamic\Events\Data\EntryDeleted;
use Statamic\Events\Data\FileUploaded;
use Statamic\Events\Data\GlobalsSaved;
use Statamic\Events\Data\AssetReplaced;
use Statamic\Events\Data\AssetUploaded;
use Statamic\Events\Data\FieldsetSaved;
use Statamic\Events\Data\SettingsSaved;
use Statamic\Events\Data\TaxonomySaved;
use Statamic\Events\Data\GlobalsDeleted;
use Statamic\Events\Data\UserGroupSaved;
use Statamic\Events\Data\CollectionSaved;
use Statamic\Events\Data\FieldsetDeleted;
use Statamic\Events\Data\SubmissionSaved;
use Statamic\Events\Data\TaxonomyDeleted;
use Statamic\Events\Data\AssetFolderSaved;
use Statamic\Events\Data\UserGroupDeleted;
use Statamic\Events\Data\CollectionDeleted;
use Statamic\Events\Data\SubmissionDeleted;
use Statamic\Events\Data\AddonSettingsSaved;
use Statamic\Events\Data\AssetFolderDeleted;
use Statamic\Events\Data\AssetContainerSaved;
use Statamic\Events\Data\AssetContainerDeleted;

class HookerListener extends Listener
{
    /**
     * The events to be listened for, and the methods to call.
     *
     * @var array
     */
    public $events = [
        AddonSettingsSaved::class    => 'addonSettings.saved',
        AssetContainerDeleted::class => 'assetContainer.deleted',
        AssetContainerSaved::class   => 'assetContainer.saved',
        AssetDeleted::class          => 'asset.deleted',
        AssetFolderDeleted::class    => 'assetFolder.deleted',
        AssetFolderSaved::class      => 'assetFolder.saved',
        AssetMoved::class            => 'asset.moved',
        AssetReplaced::class         => 'asset.replaced',
        AssetUploaded::class         => 'asset.uploaded',
        CollectionDeleted::class     => 'collection.deleted',
        CollectionSaved::class       => 'collection.saved',
        EntryDeleted::class          => 'entry.deleted',
        EntrySaved::class            => 'entry.saved',
        FieldsetDeleted::class       => 'fieldset.deleted',
        FieldsetSaved::class         => 'fieldset.saved',
        FileUploaded::class          => 'file.uploaded',
        GlobalsDeleted::class        => 'globals.deleted',
        GlobalsSaved::class          => 'globals.saved',
        PageDeleted::class           => 'page.deleted',
        PageMoved::class             => 'page.moved',
        PageSaved::class             => 'page.saved',
        PagesMoved::class            => 'pages.moved',
        RoleDeleted::class           => 'role.deleted',
        RoleSaved::class             => 'role.saved',
        SettingsSaved::class         => 'settings.saved',
        SubmissionDeleted::class     => 'submission.deleted',
        SubmissionSaved::class       => 'submission.saved',
        TaxonomyDeleted::class       => 'taxonomy.deleted',
        TaxonomySaved::class         => 'taxonomy.saved',
        TermDeleted::class           => 'term.deleted',
        TermSaved::class             => 'term.saved',
        UserDeleted::class           => 'user.deleted',
        UserGroupDeleted::class      => 'userGroup.deleted',
        UserGroupSaved::class        => 'userGroup.saved',
        UserSaved::class             => 'user.saved'
    ];

    public function __call($action, $event)
    {
        list($type, $action) = explode('.', $action);

        $this->invoke($type, $action, $event);
    }

    /**
     * Invoke
     *
     * @param $type
     * @param $action
     * @param $event
     */
    protected function invoke($type, $action, $event)
    {
        if (!$this->getConfig('active')) {
            return;
        }

        collect($this->getConfig('webhooks'))->each(function ($webhook) use ($type, $action, $event) {
            $webhook = collect($webhook);

            if ($webhook->has($action) && in_array($type, $webhook->get($action))) {
                $this->trigger($webhook->get('url'), $type, $action, $event, $webhook->get('hidden_keys') ?? []);
            }
        });
    }

    /**
     * Trigger the webhook
     *
     * @param $url
     * @param $type
     * @param $action
     * @param $event
     */
    protected function trigger($url, $type, $action, $event, $hidden)
    {
        $client = new Client();

        $data = reset($event)->data->toArray();
        $original = reset($event)->original;

        foreach ($hidden as $key) {
            if (isset($data[$key])) {
                unset($data[$key]);
            }
            if (isset($original[$key])) {
                unset($original[$key]);
            }
        }

        $client->post($url, [
            RequestOptions::JSON => [
                'type'     => $type,
                'action'   => $action,
                'data'     => reset($event)->data->toArray(),
                'original' => reset($event)->original
            ]
        ]);
    }
}
