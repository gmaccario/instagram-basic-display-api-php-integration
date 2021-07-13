<?php 

namespace App;

class Utility
{
    // Filter media type by $filter from IG API
    public function filterMedia(array $media, string $targetDir, array $exclude = array(), $filter = 'IMAGE', $action = 'save-media')
    {
        $mediaOutput = array();
        foreach($media as $m)
        {
            if($m->media_type == $filter)
            {
                $originalFilename = basename(parse_url($m->media_url, PHP_URL_PATH));

                //echo $m->media_url . " ||| " . $originalFilename . "<br />";
                if(in_array($originalFilename, $exclude))
                {
                    continue;
                }
                
                $ext = pathinfo($originalFilename, PATHINFO_EXTENSION);

                $m->filename = substr($this->slugify($m->caption), 0, 75) . '.' . $ext;

                if($action != 'save-media')
                {
                    // Set output image
                    $mediaOutput[] = $m;
                }
                else {
                    // Save image
                    $result = $this->createFolderIfNotExists($targetDir);
                    $result = $this->convertJpgToWebpORPng($targetDir, $m->filename, $m->media_url);

                    // New webp extension
                    $m->filename = str_replace('jpg', $result['conversion'], $m->filename);

                    if(file_exists($result['path']) !== false)
                    {
                        // Set output image
                        $mediaOutput[] = $m;
                    }
                }
            }
        }

        // Collect info
        if($action == 'save-media')
        {
            file_put_contents($targetDir . 'info.json', json_encode($mediaOutput));
        }


        return $mediaOutput;
    }

    public function slugify(string $text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    private function convertJpgToWebpORPng($targetDir, $filename, $fileurl)
    {
        $jpg = \imagecreatefromjpeg($fileurl);

        \imagepalettetotruecolor($jpg);
        \imagealphablending($jpg, true);
        \imagesavealpha($jpg, true);

        $path = '';
        $conversion = '';
        if(function_exists('imagewebp'))
        {
            $conversion = 'webp';

            $path = $targetDir . str_replace('jpg', $conversion, $filename);

            \imagewebp($jpg, $path, 100);
        }
        else {
            $conversion = 'png';

            $path = $targetDir . str_replace('jpg', $conversion, $filename);

            \imagepng($jpg, $path, 7);
        }

        \imagedestroy($jpg);

        return array(
            'path'          => $path,
            'conversion'    => $conversion,
        );
    }

    private function createFolderIfNotExists(string $path)
    {
        if (!file_exists($path)) 
        {
            mkdir($path, 0777, true);
        }
    }
}