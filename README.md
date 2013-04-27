so, this is a script I wrote to generate calculated average score data with match results from FIRST's servers for the 2013 FRC ultimate ascent game. it'll probably work with slight alterations
for the 2014 game but since I'm gone you're on your own.

there may be slight discrepancies in accuracy of the data; I haven't compared what I get with what somebody smarter gets, and I came across
a few instances of the php empty() function lying to me. but afaik it works acceptably.

the included index.php file is just an example I threw together, it'll let you view the data in tables, both before and after processing for OPR.
it also includes the competition's match schedule in an iframe on the bottom. it doesn't sort or rank anything in any way. the opr.php file writes the data it generates to json files so you can do whatever you want with the
data once you execute the script. I actually wrote this several months ago. if it were fresh in my mind I'd write more about doing that. bummer.
