# Svetainės vaizdas: senas titstu1/zinutes (PHP + Apache + mysqli) + dabartiniai www failai.
# Bazė prisegta per digest, kad naujas :latest nestatytų ant savęs paties.
FROM titstu1/zinutes@sha256:e1430ca77c1750b6b3550e600699aca969a4da3465ef4979f47a41040569eba6

# išvalom senus failus (index.html, zinutes.php~ ...) ir įkeliam naujus
RUN rm -rf /var/www/html/*
COPY www/ /var/www/html/

# Apache turi galėti skaityti visus failus (kitaip 403 Forbidden)
RUN chmod -R a+rX /var/www/html
