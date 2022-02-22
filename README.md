# Strider Data Engineering Assessment

Hello, my name is **Wlademyr Mendes**.\
I am a Brazilian Software Engineer and this is my assessment for Strider

# Phase 1
## Installation

Run the instalation script. It will install all dependencies and start the docker containers using Laravel Sail

```bash
sh strider-etl build
sh strider-etl up
```
This will start the docker container managed by Laravel Sail.
If you want to stop the containers just run:
```bash
sh strider-etl down
```

## Usage

```bash
# run the ETL pipeline for the 'stream' datasource
sh strider-etl run stream
```
## Queues
The queue driver being used is **sync**. That means synchronous processing.\
In order to use the Redis driver and process the queues asynchronously you should change it on .env file.

```bash
QUEUE_CONNECTION=redis
```
The docker environment already provides a Redis container and you should only run a new queue listener on another terminal window.

```bash
sh strider-etl worker
```