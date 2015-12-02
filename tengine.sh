#!/bin/bash

wget https://dn-amh.qbox.me/@/tengine/tengine-2.1.1.tar.gz
wget https://dn-amh.qbox.me/@/tengine/ngx_cache_purge-2.3.tar.gz
wget https://dn-amh.qbox.me/@/tengine/v0.6.4.tar.gz
wget https://dn-amh.qbox.me/@/tengine/jemalloc-4.0.4.tar.bz2
wget https://dn-amh.qbox.me/@/tengine/pcre-8.38.tar.gz
wget https://dn-amh.qbox.me/@/tengine/zlib-1.2.8.tar.gz
wget https://dn-amh.qbox.me/@/tengine/openssl-1.0.2d.tar.gz

tar -zxf tengine-2.1.1.tar.gz
tar -zxf ngx_cache_purge-2.3.tar.gz
tar -zxf v0.6.4.tar.gz
tar -jxf jemalloc-4.0.4.tar.bz2
tar -zxf pcre-8.38.tar.gz
tar -zxf zlib-1.2.8.tar.gz
tar -zxf openssl-1.0.2d.tar.gz

mkdir -p /usr/local/src/{ngx_cache_purge,ngx_http_substitutions_filter_module,jemalloc,pcre,zlib,openssl}
cp -a ngx_cache_purge-2.3/* /usr/local/src/ngx_cache_purge
cp -a ngx_http_substitutions_filter_module-0.6.4/* /usr/local/src/ngx_http_substitutions_filter_module
cp -a jemalloc-4.0.4/* /usr/local/src/jemalloc
cp -a pcre-8.38/* /usr/local/src/pcre
cp -a zlib-1.2.8/* /usr/local/src/zlib
cp -a openssl-1.0.2d/* /usr/local/src/openssl

chmod 755 -R /usr/local/src/jemalloc

cd tengine-2.1.1
./configure --prefix=/usr/local/tengine --user=nginx --group=nginx --with-http_ssl_module --with-http_spdy_module --with-http_concat_module --with-http_gzip_static_module --with-http_realip_module --with-http_sub_module --with-http_stub_status_module --with-http_flv_module --with-http_mp4_module --with-http_gunzip_module --with-http_addition_module --with-http_secure_link_module --with-mail --with-mail_ssl_module --with-ipv6 --with-pcre=/usr/local/src/pcre --with-zlib=/usr/local/src/zlib --with-openssl=/usr/local/src/openssl --with-jemalloc=/usr/local/src/jemalloc --without-http_uwsgi_module --without-http_scgi_module --add-module=/usr/local/src/ngx_cache_purge --add-module=/usr/local/src/ngx_http_substitutions_filter_module;

make
make install

# /usr/local/tengine/sbin/nginx -V
