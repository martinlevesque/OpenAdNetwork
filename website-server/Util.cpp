
#include <vector>
#include <time.h>
#include <iostream>
#include <stdlib.h>
#include <sstream>

#include "Util.h"



using namespace std;

int Util::GetRand(int min, int max)
{
  static int Init = 0;
  int rc;
  
  if (Init == 0)
  {
    /*
     *  As Init is static, it will remember it's value between
     *  function calls.  We only want srand() run once, so this
     *  is a simple way to ensure that happens.
     */
    srand(time(NULL));
    Init = 1;
  }

  /*
   * Formula:  
   *    rand() % N   <- To get a number between 0 - N-1
   *    Then add the result to min, giving you 
   *    a random number between min - max.
   */  
  rc = (rand() % (max - min + 1) + min);
  
  return (rc);
}

std::vector<std::string> &Util::split(const std::string &s, char delim, std::vector<std::string> &elems) {
    std::stringstream ss(s);
    std::string item;
    while(std::getline(ss, item, delim)) {
        elems.push_back(item);
    }
    return elems;
}


std::vector<std::string> Util::split(const std::string &s, char delim) {
    std::vector<std::string> elems;
    return split(s, delim, elems);
}

