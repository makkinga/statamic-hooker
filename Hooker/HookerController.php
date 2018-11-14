<?php

namespace Statamic\Addons\Hooker;

use Statamic\Extend\Controller;

class HookerController extends Controller
{
    /**
     * Maps to your route definition in routes.yaml
     *
     * @return mixed
     */
    public function index()
    {
        return $this->view('index');
    }
}
