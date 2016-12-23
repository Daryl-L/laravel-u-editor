<?php
/**
 *
 *
 * @version
 * @author: daryl
 * @date: 16/12/23
 * @since:
 */

namespace Stevenyangecho\UEditor\Uploader;

trait UploadFtp
{
    public function uploadFtp($host, $storage, $content)
    {
        $storage->put('/' . $host . $this->fullName, $content);
        $this->fullName='http://'.$host.'/'.ltrim($this->fullName, '/');
        $this->stateInfo = $this->stateMap[0];
        return true;
    }
}