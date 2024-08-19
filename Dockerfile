docker run -d \
    -v /var/run/docker.sock:/var/run/docker.sock \
    -v coolify-db:/db \
    -p 3000:3000 \
    coollabsio/coolify
