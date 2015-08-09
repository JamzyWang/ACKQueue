# ACKQueue

ACKQueue is a redis-based message queue. It's used as a simple distributed message queue in our duplicate image check project. This repository is the basic version of ACKQueue.

ACKQueue use three redis lists to store messages:

 - messageQueue: store messages which needs to be processed
 - ackQueue: messages which have been assigned to worker process but haven't returned execute result
 - failedQueue: messages which failed to execute