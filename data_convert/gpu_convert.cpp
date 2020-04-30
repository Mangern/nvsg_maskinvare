#include <iostream>
#include <string>

using namespace std;




int main() {
	string in;
	
	while (getline(cin, in)) {
		int i = in.find("\">");
		i = in.find("\">", i + 1) + 2;
		int j = in.find("</A>", i);

		string name = in.substr(i, j - i);

		i = in.find("<TD>", j) + 4;
		j = in.find("</TD>", i);

		string rank = in.substr(i, j - i);

		cout << name << endl;
		cout << rank << endl;
		
	}

	return 0;
}