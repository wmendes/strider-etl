extract 
create data sources config file
create a job to check data sources
create a job to send files to staging area

transform
dispatch transform jobs

load
load job


creates a new 

harvest
    id
    timestamps
    source
    status
    stage
    data

extract
    id
    harvest_id
    timestamps
    data
    status

transform
    id
    harvest_id
    timestamps
    data
    status

load
    id
    harvest_id
    timestamps
    data
    status



