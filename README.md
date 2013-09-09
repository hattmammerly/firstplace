Currently running at [http://opr.hattmammerly.com/](http://opr.hattmammerly.com/)

This script reads match results off of FIRST's servers, does some math (Gaussian elimination!) and produces calculated average scores.  
This was written for the 2013 game, but it will likely work (with a few alterations) for the 2014 game as well.

There may be slight discrepancies in accuracy of the data; I came across a few instances where PHP's empty() was ostensibly lying to me but never probed.

The included index.php file is just an example I threw together to view the data in tables, both before and after processing for OPR.
It also includes the competition's match schedule in an iframe on the bottom. It doesn't sort or rank anything in any way. The opr.php file writes the data it generates to json files so you can do whatever you want with the
data once you execute the script. This actually was written several months before initially being put on GitHub; if it were fresh in my mind I'd write more. bummer.
