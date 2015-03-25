#include <sys/types.h>
#include <sys/sem.h>

class Semaphore
{
private:
    key_t key;
    int nSems;
    struct sembuf sb;
    int semid;

    int initSem();

public:
    Semaphore(int);
	Semaphore();

    void getLock();

    void ReleaseLock();
};
