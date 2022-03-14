FROM nginx:latest

RUN mkdir /etc/nginx/snippets

COPY ./build/nginx/nginx.conf /etc/nginx/
COPY ./build/nginx/www.conf /etc/nginx/conf.d/default.conf
COPY ./build/nginx/snippets/fastcgi-php.conf /etc/nginx/snippets/fastcgi-php.conf
COPY ./build/nginx/fastcgi.conf /etc/nginx/fastcgi.conf