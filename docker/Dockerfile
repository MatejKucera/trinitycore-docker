FROM debian:buster as env

RUN apt-get update && \
     apt-get install -y git clang cmake make gcc g++ libmariadbclient-dev libssl-dev libbz2-dev libreadline-dev libncurses-dev libboost-all-dev p7zip default-libmysqlclient-dev mariadb-client
RUN update-alternatives --install /usr/bin/cc cc /usr/bin/clang 100 && \
    update-alternatives --install /usr/bin/c++ c++ /usr/bin/clang 100

RUN mkdir /wow && \
    git clone -b 3.3.5 git://github.com/TrinityCore/TrinityCore.git /repository && \
    mkdir -p /repository/build && cd /repository/build && cmake ../ -DCMAKE_INSTALL_PREFIX=/wow && make && make -j $(nproc) install

COPY docker/script/wait-for-it.sh /wait-for-it.sh
RUN chmod +x /wait-for-it.sh

ENTRYPOINT [ "sh", "/entrypoint.sh" ]




FROM env AS auth

COPY docker/script/entrypoint-auth.sh /entrypoint.sh




FROM env AS world

COPY docker/script/entrypoint-world.sh /entrypoint.sh




FROM env AS extract

COPY docker/script/entrypoint-extract.sh /entrypoint.sh