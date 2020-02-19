FROM debian:buster as env

RUN apt-get update
RUN apt-get install -y git clang cmake make gcc g++ 
RUN apt-get install -y libmariadbclient-dev libssl-dev libbz2-dev libreadline-dev libncurses-dev libboost-all-dev 
RUN apt-get install -y p7zip default-libmysqlclient-dev
RUN update-alternatives --install /usr/bin/cc cc /usr/bin/clang 100
RUN update-alternatives --install /usr/bin/c++ c++ /usr/bin/clang 100

RUN mkdir /wow
RUN git clone -b 3.3.5 git://github.com/TrinityCore/TrinityCore.git /repository
RUN mkdir -p /repository/build && cd /repository/build && cmake ../ -DCMAKE_INSTALL_PREFIX=/wow && make && make -j $(nproc) install

RUN apt-get install -y mariadb-client

COPY docker/script/wait-for-it.sh /wait-for-it.sh
RUN chmod +x /wait-for-it.sh

FROM env AS auth

COPY docker/script/entrypoint-auth.sh /entrypoint.sh
ENTRYPOINT [ "sh", "/entrypoint.sh" ]

FROM env AS world

COPY docker/script/entrypoint-world.sh /entrypoint.sh
ENTRYPOINT [ "sh", "/entrypoint.sh" ]


FROM env AS extract

COPY docker/script/entrypoint-extract.sh /entrypoint.sh
ENTRYPOINT [ "sh", "/entrypoint.sh" ] 