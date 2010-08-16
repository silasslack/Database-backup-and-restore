-- Host: localhost
-- Generation Time: Aug 16, 2010 at 05:01 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `EXPEDITING`
--

-- --------------------------------------------------------

--
-- Table structure for table `backup_history`
--

CREATE TABLE IF NOT EXISTS `backup_history` (
  `filename` varchar(255) NOT NULL,
  `datecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `number` int(5) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`number`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `backup_history`
--


