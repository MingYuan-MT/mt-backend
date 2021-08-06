# MT-Backend

## 本地环境安装说明

>推荐使用 docker-composer 容器编排进行本地环境部署

1、~~配置域名~~

调整当前目录下的 `mt-backend.conf` 文件，默认是本地开发使用 `localhost` 域名，该步骤可以忽略
- php_study 伪静态配置
    ```conf
    root {项目目录}/mt/mt-backend/public;
    index index.php index.html index.htm;

    location /
        {
            try_files $uri $uri/ /index.php$is_args$args;
        }        

    location ~ \.php(.*)$ {
            fastcgi_pass   127.0.0.1:9009;
            fastcgi_index  index.php;
            fastcgi_split_path_info  ^((?U).+\.php)(/?.+)$;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            fastcgi_param  PATH_INFO  $fastcgi_path_info;
            fastcgi_param  PATH_TRANSLATED  $document_root$fastcgi_path_info;
            include        fastcgi_params;
        }
    ```

2、域名解析

修改计算机 `hosts` 文件，添加以下配置 `127.0.0.1 localhost`

1、创建镜像

>依赖工具安装

- Docker [https://docs.docker.com/install/]
- Docker-compose [https://docs.docker.com/compose/install/#install-compose]

> 工具安装完成后在当前目录执行以下命令即可

    docker-compose up -d 

### Composer 执行说明

原则上 **禁止** 使用 `composer update`，需要安装包直接执行 `composer require` 安装完毕后需执行 `composer dumpautoload -a`
- php版本要求>8.0
- php扩展需要开启 `mbstring` `curl` `fileinfo` `gd` `openssl` `PDO` `json` `json` `Phar` `bcmath` `Core` `date` 有报错，可执行 `composer install -vvv` 查看，开启对应的扩展。
