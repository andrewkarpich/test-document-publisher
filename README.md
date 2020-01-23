# test-document-publisher

## How to start a project

* Install docker
* Up docker 
* Clone this repo
* Up a project with command:

```
docker-compose up --build
```

API available on **http://localhost:8080/api/v1/document**

You can run tests on command:

```
docker exec -it d_backend php vendor/bin/codecept run
```

## Spent time

| # | Task | Estimation | Spend | Comment |
|---|------|------------|-------|---------|
| 1 | Create project | 1h | 3h | It was supposed to take an existing project, but had to re-build the project |
| 2 | Create domain structure | 1h | 1h | - |
| 3 | Implementation infrastructure | 2h | 3h | Create services, repositories ... |
| 4 | API implementation | 1h | 1h | - |
| 5 | Debug application && write api and unit tests | 2h | 4h | Most of the time I solved problems arising between the Swoole and the Phalcon |
| 5 | Cleanup of code and other | 1h | 1h | - |