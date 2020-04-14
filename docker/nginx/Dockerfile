FROM nginx:1.17.9-alpine

COPY nginx.conf /etc/nginx/

EXPOSE 80
STOPSIGNAL SIGTERM

CMD ["nginx", "-g", "daemon off;"]