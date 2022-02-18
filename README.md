# Strider Data Engineering Assessment

Hello, my name is Wlademyr and this is my assessment for Strider

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
import foobar

# run the ETL pipeline for the 'stream' datasource
sh strider-etl run stream
```
