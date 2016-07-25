-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 07, 2014 at 03:13 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Dumping data for table `beers`
--

INSERT INTO `beers` (`name`, `beerStyleId`, `ogEst`, `fgEst`, `srmEst`, `ibuEst`, `notes`, `createdDate`, `modifiedDate`) VALUES
('Darth Vader', '80', '1.066', '1.016', '38.0', '66.0', 'Rich, toasty malt flavor. Generous amounts of pine, citrus and roasted coffee. Herbal aroma with a punch of IPA hops at the finish.', NOW(), NOW() ),
('Row 2 Hill 56', '33', '1.055', '1.010', '5.1', '40', '100% Simcoe hops make up this beer from start to finish! It is named for the location in the experimental hop yard in Yakima, WA, where it was first created.', NOW(), NOW() ),
('Loon Lake Porter', '78', '1.050', '1.012', '24', '24.6', 'With a low IBU and a mellow base recipe, this is a beer that can be turned from grain to glass quickly. The smoke aroma is prominent, but not at all overpowering. The sweetness of the malt really balances this beer well.', NOW(), NOW() ),
('Reaper''s Mild', '36', '1.035', '1.012', '19.1', '20.4', 'A full flavored session beer that is both inexpensive to brew and quick to go from grain to glass. Ready to drink in a couple weeks, if you push it.', NOW(), NOW() ),
('Deception Cream Stout', '43', '1.058', '1.020', '36', '27', 'Coffee and chocolate hit you up front intermingled with smooth caramel flavors that become noticeable mid-palate. Nice roasty finish rounds it out. Balanced and not cloying at all, but obviously leaning slightly to the sweeter side. Very smooth and creamy.', NOW(), NOW() ),
('Haus Pale Ale', '33', '1.051', '1.011', '5.0', '39.0', 'Pale straw-gold color with two fingers of fluffy white head. Bread dough and cracker aromas up front, followed immediately by pine and grapefruit. Clean, crisp and dangerously sessionable.', NOW(), NOW() ),
('Two Hearted Ale', '49', '1.055', '1.014', '5.6', '52.6', 'American malts and enormous hop additions give this beer a crisp finish and an incredibly floral aroma.', NOW(), NOW() ),
('Skeeter Pee', '100', '1.070', '1.009', '0', '0', 'The original, easy to drink, naturally fermented lemon drink. Bitter, sweet, and a kick in the teeth. This hot-weather thirst quencher puts commercialized lemon-flavored malt beverages to shame.', NOW(), NOW() ),
('Black Peach Tea', '102', '1.000', '1.000', '0', '0', 'Black tea infused with the unmistakable summertime flavor of juicy, orchard-fresh peaches and just the right amount of natural milled cane sugar.', NOW(), NOW() ),
('Aloha Morning', '105', '1.000', '1.000', '0', '0', 'Children''s strawberry and citrus punch, thinned to suit an adult pallet using only the highest quality dihydrogen monoxide available.', NOW(), NOW() );

-- --------------------------------------------------------

--
-- Dumping data for table `kegTypes`
--

INSERT INTO `kegs` ( label, kegTypeId, notes, kegStatusCode, beerId, createdDate, modifiedDate ) VALUES
( '1', '1', 'One hanndle cracked', 'SERVING', '1', NOW(), NOW() ),
( '2', '1', 'Green handles', 'SERVING', '2', NOW(), NOW() ),
( '3', '1', '', 'SERVING', '3', NOW(), NOW() ),
( '4', '1', '', 'SERVING', '4', NOW(), NOW() ),
( '5', '1', 'Green handles', 'SERVING', '5', NOW(), NOW() ),
( '6', '1', '', 'SERVING', '6', NOW(), NOW() ),
( '7', '1', '', 'SERVING', '7', NOW(), NOW() ),
( '8', '1', '', 'SERVING', '8', NOW(), NOW() ),
( '9', '1', 'Blue handles', 'SERVING', '9', NOW(), NOW() ),
( '10', '1', '', 'SERVING', '10', NOW(), NOW() ),
( '11', '1', 'Green handles', 'SECONDARY', '1', NOW(), NOW() ),
( '12', '1', '', 'SECONDARY', '2', NOW(), NOW() ),
( '13', '1', '', 'DRY_HOPPING', '3', NOW(), NOW() ),
( '14', '1', '', 'DRY_HOPPING', '4', NOW(), NOW() ),
( '15', '1', '', 'CONDITIONING', '5', NOW(), NOW() ),
( '16', '1', '', 'CONDITIONING', '6', NOW(), NOW() ),
( '17', '1', '', 'CLEAN', NULL, NOW(), NOW() ),
( '18', '1', '', 'CLEAN', NULL, NOW(), NOW() ),
( '19', '1', '', 'CLEAN', NULL, NOW(), NOW() ),
( '20', '1', '', 'CLEAN', NULL, NOW(), NOW() ),
( '21', '1', '', 'CLEAN', NULL, NOW(), NOW() ),
( '22', '1', '', 'NEEDS_CLEANING', '2', NOW(), NOW() ),
( '23', '1', 'Leaks at pressure relief valve', 'NEEDS_PARTS', NULL, NOW(), NOW() ),
( '24', '1', 'Leaks at lid/body interface when < 15 PSI', 'NEEDS_REPAIRS', NULL, NOW(), NOW() );


-- --------------------------------------------------------

--
-- Put all beers into the `taps` table
--

INSERT INTO taps(`kegId`, `tapNumber`, `active`, `startAmount`, `currentAmount`, `createdDate`, `modifiedDate`)
SELECT kegs.id as kegId, kegs.id as tapNumber, 1 as active, kegs.startAmount as startAmount, kegs.startAmount as currentAmount, NOW() as createdDate, NOW() as modifiedDate
FROM (SELECT k.*, kt.maxAmount as startAmount FROM kegs k LEFT JOIN kegTypes kt ON kt.id = k.kegTypeId ORDER BY Id) as kegs;

-- --------------------------------------------------------

--
-- Add some bottles to `bottles` table
--

INSERT INTO `bottles` ( bottleTypeId, beerId, capRgba, capNumber, startAmount, currentAmount, active, createdDate, modifiedDate ) VALUES
( '1', '1', '200,000,000,0.5', '1', '16', '16', '1', NOW(), NOW() ),
( '2', '2', '000,200,000,0.5', NULL, '8', '6', '1', NOW(), NOW() ),
( '1', '3', '000,000,200,0.5', NULL, '32', '12', '1', NOW(), NOW() ),
( '2', '4', '200,200,200,0.5', NULL, '4', '1', '1', NOW(), NOW() );

-- --------------------------------------------------------

--
-- Add number of taps to `config`
--

UPDATE `config` SET configValue='10' WHERE configname='numberOfTaps';


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
