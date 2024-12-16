class Date
{
private:
    int j;    
    int m;    
    int a;    
    int h;    
    int min; 

    // sh7al mn nhar fshher
    int daysInMonth(int month) const;

public:
    
    Date(int u = 1, int v = 1, int w = 2000, int x = 0, int y = 0);
    bool operator==(const Date& d) const;
    bool operator>(const Date& d) const;
    friend std::istream& operator>>(std::istream& in, Date& d);
    friend std::ostream& operator<<(std::ostream& out, const Date& d);
};


int Date::daysInMonth(int month) const
{
    switch (month)
    {
    case 1: case 3: case 5: case 7: case 8: case 10: case 12:
        return 31;
    case 4: case 6: case 9: case 11:
        return 30;
    case 2:
        return 28; // mafiya li dir leap years (fevrier 29 isha3a)
    default:
        return 0;
    }
}

Date::Date(int u, int v, int w, int x, int y) : j(u), m(v), a(w), h(x), min(y) {}

// == operator surcharge
bool Date::operator==(const Date& d) const
{
    return (d.j == j && d.m == m && d.a == a && d.h == h && d.min == min);
}

// > operator surcharge
bool Date::operator>(const Date& d) const
{
    if (a > d.a) return true;
    if (a < d.a) return false;
    
    if (m > d.m) return true;
    if (m < d.m) return false;
    
    if (j > d.j) return true;
    if (j < d.j) return false;
    
    if (h > d.h) return true;
    if (h < d.h) return false;
    
    return min > d.min;
}

// >> operator surcharge for input
std::istream& operator>>(std::istream& in, Date& d)
{
    int day, month, year, hour, minute;
    std::cout << "Enter date (day month year hour minute): ";
    in >> day >> month >> year >> hour >> minute;

    if (month < 1 || month > 12 || day < 1 || day > d.daysInMonth(month) ||
        hour < 0 || hour > 23 || minute < 0 || minute > 59)
    {
        std::cout << "Error: Invalid date or time." << std::endl;
    }
    else
    {
        d.j = day;
        d.m = month;
        d.a = year;
        d.h = hour;
        d.min = minute;
    }

    return in;
}

// << operator surcharge for output
std::ostream& operator<<(std::ostream& out, const Date& d)
{
    out << d.j << "/" << d.m << "/" << d.a << " " << d.h << ":" << d.min;
    return out;
}

