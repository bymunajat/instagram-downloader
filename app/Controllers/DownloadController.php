<?php
namespace App\Controllers;

use App\Services\CobaltService;

class DownloadController
{
    protected $cobalt;

    public function __construct()
    {
        $this->cobalt = new CobaltService();
    }

    public function download($url)
    {
        return $this->cobalt->downloadInstagram($url);
    }
}
