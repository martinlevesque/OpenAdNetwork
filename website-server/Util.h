
#include <vector>
#include <string>

#ifndef UTIL_H
#define UTIL_H

class Util
{
public:
	static int GetRand(int min, int max);
	static std::vector<std::string> split(const std::string &s, char delim);
private:
	static std::vector<std::string>& split(const std::string &s, char delim, std::vector<std::string> &elems);

};

#endif
