# Duomenų bazės vaizdas: senas titstu1/zinuciu-db (MySQL 8.4, DB stud, vartotojas stud/stud)
# + dabartinė DB kopija (lentelės ir žinutės).
FROM titstu1/zinuciu-db@sha256:10e562b059fd773c48c3e66c0ff75d42790eea45bc15d6cf9b97f51c7da84a9f

# MySQL paleidžia šiuos .sql failus TIK pirmą kartą, kai duomenų tomas (volume) tuščias.
RUN rm -f /docker-entrypoint-initdb.d/*
COPY db/zinutes.sql /docker-entrypoint-initdb.d/zinutes.sql
RUN chmod a+r /docker-entrypoint-initdb.d/zinutes.sql
