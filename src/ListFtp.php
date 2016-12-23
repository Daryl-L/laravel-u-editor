<?php
/**
 *
 *
 * @version
 * @author: daryl
 * @date: 16/12/23
 * @since:
 */

namespace Stevenyangecho\UEditor;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\Strategy;

class ListFtp
{
    public function __construct($allowFiles, $listSize, $path, $request)
    {
        $this->allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);
        $this->listSize = $listSize;
        $this->path = ltrim($path,'/');
        $this->request = $request;
    }

    public function getList()
    {
        $size = $this->request->get('size', $this->listSize);
        $start = $this->request->get('start', '');

        $items = $this->getfiles(Storage::disk('ftpUEditor'), '/' . session('siteHost'));
        $files=[];
        foreach ($items as  $item) {
            $item = str_replace(session('siteHost'), '', $item);
            $files[] = array(
                'url' => 'http://' . session('siteHost') . '/' . $item,
                'mtime' => '',
            );
        }
        if(empty($files)){
            return [
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => 0
            ];
        }
        /* 返回数据 */
        $result = [
            "state" => "SUCCESS",
            "list" => $files,
            "start" => $start,
            "total" => count($files)
        ];

        return $result;
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    protected function getfiles($storage, $path)
    {
        $files = [];
        $dirs = $storage->directories($path);
        foreach ($dirs as $dir) {
            $files = array_merge($files, $this->getfiles($storage, $dir));
        }

        $srcFiles = $storage->files($path);
        foreach ($srcFiles as $srcFile) {
            $files[] = $srcFile;
        }

        return $files;
    }
}