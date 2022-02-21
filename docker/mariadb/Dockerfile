# Use this image as base
FROM mariadb:10.5

# Use this root password
ARG MYSQL_ROOT_PASSWORD

# Custom MariaDB configuration
COPY mariadb/mariadb.cnf /etc/mysql/conf.d/mariadb.cnf
COPY mariadb/my.cnf /root/.my.cnf
RUN sed -Ei "s/^password.+\$/password = \"${MYSQL_ROOT_PASSWORD}\"/" /root/.my.cnf

RUN cat /root/.my.cnf

RUN chmod 0600 /root/.my.cnf
