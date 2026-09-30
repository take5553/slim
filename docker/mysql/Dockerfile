FROM mysql:26.7.0

USER root
RUN microdnf update -y \
    && microdnf install -y tzdata glibc-langpack-ja \
    && microdnf clean all

ENV TZ=Asia/Tokyo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ENV LANG=ja_JP.UTF-8
ENV LANGUAGE=ja_JP:ja
ENV LC_ALL=ja_JP.UTF-8

USER mysql
