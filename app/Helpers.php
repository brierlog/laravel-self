<?php

function isDebug()
{
    return config('app.debug');
}

// 判断是否开发环境
if (! function_exists('isDev')) {
    function isDev()
    {
        return 'dev' == env('APP_ENV');
    }
}
// 判断是否生产环境
if (! function_exists('isProd')) {
    function isProd()
    {
        return 'prod' == env('APP_ENV');
    }
}

if (! function_exists('deleteDir')) {
    function deleteDir($path)
    {
        if (is_dir($path)) {
            //扫描一个目录内的所有目录和文件并返回数组
            $dirs = scandir($path);
            foreach ($dirs as $dir) {
                //排除目录中的当前目录(.)和上一级目录(..)
                if ('.' != $dir && '..' != $dir) {
                    //如果是目录则递归子目录，继续操作
                    $sonDir = $path.'/'.$dir;
                    if (is_dir($sonDir)) {
                        //递归删除
                        deleteDir($sonDir);
                        //目录内的子目录和文件删除后删除空目录
                        @rmdir($sonDir);
                    } else {
                        //如果是文件直接删除
                        @unlink($sonDir);
                    }
                }
            }
            @rmdir($path);
        }
    }
}
if (! function_exists('priceFormat')) {
    /**
     * @param float|string $price
     * @param int $decimals
     *
     * @return float
     */
    function priceFormat($price, $decimals = 2)
    {
        if (empty($price)) {
            return 0;
        }

        return (float) number_format($price, $decimals, '.', '');
    }
}
