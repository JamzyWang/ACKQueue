# ACKQueue

ACKQueue is a redis-based message queue. It's used as a simple distributed message queue in our duplicate image check project. This repository is the basic version of ACKQueue.

## Roles
ACKQueue uses three redis lists to store messages:

 - **messageQueue**: store messages which needs to be processed
 - **ackQueue**: messages which have been assigned to worker process but haven't returned execute result
 - **failedQueue**: messages which failed to execute
 - **Worker**: message executor
 - **ACkWorker**: ackQueue check executor

## Workflow
 The workflow of the ACKQueue is as follows:

 - 1. messages are put into messageQueue first.
 - 2. worker process gets message from messageQueue, then the message will be removed from messageQueue and be put into ackQueue
 - 3. worker process executes the message and ack the message when finish consuming the message, remove the message from the ackQueue.
 - 4. a ACKWorker daemon process will check messages in the ackQueue, when the message executing time exceeds the setting time, the message will be moved to the failedQueue.

## Usage:
 - A sample worker is in daemons/ExecutorWorker.php
 - A sample ACKWorker is in daemons/ExecutorWorker.php
