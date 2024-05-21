# syntax=docker/dockerfile:1

FROM golang:1.22.0

# Set destination for COPY
WORKDIR /

# Download Go modules
COPY go.mod go.sum ./
RUN go mod download

# Copy the source code and HTML file
COPY . .

# Build
RUN CGO_ENABLED=0 GOOS=linux go build -o /dockerandgo

# Optional: To bind to a TCP port, runtime parameters must be supplied to the docker command.
# But we can document in the Dockerfile what ports the application is going to listen on by default.
# https://docs.docker.com/reference/dockerfile/#expose
EXPOSE 8080

# Run
CMD ["/dockerandgo"]