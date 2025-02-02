-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2025 at 09:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shoes`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminid` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminid`, `username`, `password`) VALUES
(10, 'admin', 'admin'),
(12, 'Rhod', '$2y$10$27pTIMikzJWtTJUR11xXPuasd6/ul0nAgADCxv7ZgB7rw75Ek58Di');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contact_id`, `email`, `message`) VALUES
(39, '', ''),
(40, '', ''),
(41, 'jharilpinpin@yahoo.com', ''),
(42, '', ''),
(43, '', ''),
(44, '', ''),
(45, '', ''),
(46, '', ''),
(47, '', ''),
(48, '', ''),
(49, '', ''),
(50, '', ''),
(51, '', ''),
(52, '', ''),
(53, '', ''),
(54, '', ''),
(55, 'rlpanget@gmail.com', ''),
(56, '', ''),
(57, '', ''),
(58, '', ''),
(59, '', ''),
(60, '', ''),
(61, '', ''),
(62, '', ''),
(63, '', ''),
(64, '', ''),
(65, '', ''),
(66, '', ''),
(67, '', ''),
(68, '', ''),
(69, '', '1'),
(70, '', '1'),
(71, '', 'asfasfa'),
(72, '', ''),
(73, '', ''),
(74, '', ''),
(75, '', ''),
(76, '', ''),
(77, '', ''),
(78, '', ''),
(79, '', ''),
(80, '', ''),
(81, '', ''),
(82, '', ''),
(83, '', ''),
(84, '', ''),
(85, '', ''),
(86, '', ''),
(87, '', ''),
(88, '', ''),
(89, '', ''),
(90, '', ''),
(91, '', ''),
(92, '', ''),
(93, '', ''),
(94, '', ''),
(95, '', ''),
(96, '', ''),
(97, '', ''),
(98, '', ''),
(99, '', ''),
(100, '', ''),
(101, '', ''),
(102, '', ''),
(103, '', ''),
(104, '', ''),
(105, '', ''),
(106, '', ''),
(107, '', ''),
(108, '', ''),
(109, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerid` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `mi` varchar(1) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `country` varchar(50) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerid`, `firstname`, `mi`, `lastname`, `address`, `country`, `zipcode`, `mobile`, `telephone`, `email`, `password`) VALUES
(6, 'R', 'R', 'R', 'r', 'r', 'r', 'r', 'r', 'rlpanget@gmail.com', '12345'),
(7, 'jharil', 'j', 'pinpin', 'poblacion plaridel bulacan', 'bulacan', '3004', '09538000155', '123', 'jharilpinpin@yahoo.com', 'jharil12345'),
(8, 'Talle', 'S', 'Tubig', 'Masantol', 'Pampanga', '2017', '094736272', 'N/A', 'tubigtalle@gmail.com', 'admin'),
(9, 'Atticus', ' ', 'Raven', 'quezon city', 'manila', '1105', '09238421', '', 'qwqwqwqwqwqwq@gmail.com', '123456789'),
(10, 'Pan', 'L', 'Ver', 'Bukacan', 'Bulacan', '1030', '94673330001', '', 'panverano1@gmail.com', '1234567'),
(11, 'Rhod Lenard', 'V', 'Delas Nieves', '034 Avendano street', 'Bulacan', '3004', '09760048883', '12345678', 'rdelasnieves2023@student.nbscollege.edu.ph', '$2y$10$JdcA6MpVZAs6RzfS3/82Sue5lRvObScFrvhzr7rzsaTwGXdA9v6Ki');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_size` varchar(10) NOT NULL,
  `order_qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_price` varchar(50) NOT NULL,
  `product_size` varchar(255) DEFAULT NULL,
  `product_image` varchar(500) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_price`, `product_size`, `product_image`, `brand`, `category`) VALUES
(35950620, 'nike zoom ', '2000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '3936598485474861462561056_1947487452396244_8408637156408831686_n.jpg', 'Nike', 'running'),
(51807240, 'Vans KNU', '2500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '76639707382476763Knu.png', 'Vans ', 'football'),
(95798472, 'Adidas ', '2500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '54417628812010555466110427_122181261116187336_3953565432394203454_n.jpg', 'Adidas', 'running'),
(109224725, 'New Balance ', '2000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '82137191222986790450807904_122156959754187336_7221892738182386746_n.jpg', 'New Balance', 'running'),
(115925985, 'Onitsuka ', '3000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '68016117731145611onitsuka.png', 'Onitsuka', 'football'),
(158580830, 'Converse', '2000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '50500996352519288449835640_122155766774187336_1973844099569275164_n.jpg', 'Converse', 'football'),
(198883440, 'Yeezy white', '3000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '16726521146804405474625861_122196984764187336_6723816367738442418_n.jpg', 'Yeezy', 'running'),
(203821907, 'AE 1', '4000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '11397708856230762441499449_979896130499020_2201079667166261346_n.jpg', 'AE 1', 'basketball'),
(236262610, 'Lebron 20', '3000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '12389185410443833469863661_1019961823478461_8465046295829723111_n.jpg', 'Nike', 'basketball'),
(239260727, 'Adidas ', '3500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '39441438428271556470209853_1023991686408808_3721019142001919974_n.jpg', 'Adidas', 'running'),
(260402624, 'Kyrie 1 ', '3500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '73634536575684290Kyrie1.png', 'Kyrie ', 'basketball'),
(269816191, 'Nike Cortez', '2500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '24592124249963498449856170_122155771346187336_2271257309018430755_n.jpg', 'Nike', 'football'),
(285535499, 'Puma', '3000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '52720906695788574471765467_1034054412069202_2685442819752268084_n.jpg', 'Puma', 'football'),
(293848527, 'Nike Air ', '2000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '86431725269987457452157527_122159354354187336_1223703388960775354_n.jpg', 'Nike', 'football'),
(320358821, 'Kobe 11', '3000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '49898037106694283466557740_999863708821606_3683586002233114245_n.jpg', 'Kobe', 'basketball'),
(353378820, 'Sabrina', '2500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11', '34066925811684013sabrina.png', 'Sabrina', 'feature'),
(398419917, 'Nike Gt', '3500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '2183763278554342nikegtcut.png', 'Nike', 'basketball'),
(422448398, 'Kobe 9', '2500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '24063969933665535463867603_981221127352531_3796712006077300261_n.jpg', 'Kobe', 'basketball'),
(437776392, 'New Balance ', '2000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '35023906996882941450807904_122156959754187336_7221892738182386746_n.jpg', 'New Balance', 'feature'),
(446287022, 'Sport', '1500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '98436516180085501474854668_122196984374187336_4213326592118695061_n.jpg', 'Sport', 'running'),
(454312233, 'Onitsuka ', '3000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '32815543050473999onitsuka.png', 'Onitsuka', 'feature'),
(456877743, 'J1 Travis Fragment', '3500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '1324082789699149NIkeair.png', 'Jordan', 'football'),
(473184359, 'Adidas High', '3000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5', '59450019946803806449742219_122154870602187336_6421464602731655693_n.jpg', 'Adidas', 'running'),
(557326675, 'Curry', '3500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '87928971299168700470191745_1023276579813652_1145497151134725404_n.jpg', 'Curry', 'basketball'),
(624151402, 'Giannis', '2500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '31918467693525177470241575_1023991153075528_102940168185031159_n.jpg', 'Nike', 'basketball'),
(661619842, 'Samba ', '2500', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '73657427192030980samba.png', 'Adidas', 'football'),
(829082289, 'Yeezy', '2000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '29794965147027224449707406_122154870362187336_6846915699252259178_n.jpg', 'Yeezy', 'running'),
(929797644, 'J1 Low', '3000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '68071123731961483467291251_1002752105199433_270795246232220402_n.jpg', 'Jordan', 'football'),
(944470087, 'Hoka', '2000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '10952044557379279467312562_1002751268532850_6109112710819289515_n.jpg', 'Hoka', 'running'),
(955982345, 'Kobe 5 ', '3000', 'US 7,US 7.5,US 8,US 8.5,US 9,US 9.5,US 10,US 10.5,US 11,US 11.5,US 12', '8109400629923293kobev.png', 'Kobe', 'basketball');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_size` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock_id`, `product_id`, `product_size`, `qty`) VALUES
(386, 398419917, 'US 7', 1),
(377, 353378820, 'US 7', 3),
(378, 353378820, 'US 7.5', 3),
(379, 353378820, 'US 8', 3),
(380, 353378820, 'US 8.5', 3),
(381, 353378820, 'US 9', 3),
(382, 353378820, 'US 9.5', 3),
(383, 353378820, 'US 10', 3),
(384, 353378820, 'US 10.5', 3),
(385, 353378820, 'US 11', 3),
(387, 398419917, 'US 7.5', 1),
(388, 398419917, 'US 8', 1),
(389, 398419917, 'US 8.5', 1),
(390, 398419917, 'US 9', 1),
(391, 398419917, 'US 9.5', 1),
(392, 398419917, 'US 10', 1),
(393, 398419917, 'US 10.5', 1),
(394, 398419917, 'US 11', 1),
(395, 398419917, 'US 11.5', 1),
(396, 398419917, 'US 12', 1),
(484, 437776392, 'US 12', 3),
(483, 437776392, 'US 11.5', 3),
(482, 437776392, 'US 11', 3),
(481, 437776392, 'US 10.5', 3),
(480, 437776392, 'US 10', 3),
(479, 437776392, 'US 9.5', 3),
(478, 437776392, 'US 9', 3),
(477, 437776392, 'US 8.5', 3),
(476, 437776392, 'US 8', 3),
(475, 437776392, 'US 7.5', 3),
(474, 437776392, 'US 7', 3),
(473, 109224725, 'US 12', 3),
(472, 109224725, 'US 11.5', 3),
(471, 109224725, 'US 11', 3),
(470, 109224725, 'US 10.5', 3),
(469, 109224725, 'US 10', 3),
(468, 109224725, 'US 9.5', 3),
(467, 109224725, 'US 9', 3),
(466, 109224725, 'US 8.5', 3),
(465, 109224725, 'US 8', 3),
(464, 109224725, 'US 7.5', 3),
(463, 109224725, 'US 7', 3),
(462, 829082289, 'US 12', 3),
(461, 829082289, 'US 11.5', 3),
(460, 829082289, 'US 11', 3),
(459, 829082289, 'US 10.5', 3),
(458, 829082289, 'US 10', 3),
(457, 829082289, 'US 9.5', 3),
(456, 829082289, 'US 9', 3),
(455, 829082289, 'US 8.5', 3),
(454, 829082289, 'US 8', 3),
(453, 829082289, 'US 7.5', 3),
(452, 829082289, 'US 7', 3),
(451, 269816191, 'US 12', 3),
(450, 269816191, 'US 11.5', 3),
(449, 269816191, 'US 11', 3),
(448, 269816191, 'US 10.5', 3),
(447, 269816191, 'US 10', 3),
(446, 269816191, 'US 9.5', 3),
(445, 269816191, 'US 9', 3),
(444, 269816191, 'US 8.5', 3),
(443, 269816191, 'US 8', 3),
(442, 269816191, 'US 7.5', 3),
(441, 269816191, 'US 7', 3),
(485, 454312233, 'US 7', 3),
(486, 454312233, 'US 7.5', 3),
(487, 454312233, 'US 8', 3),
(488, 454312233, 'US 8.5', 3),
(489, 454312233, 'US 9', 3),
(490, 454312233, 'US 9.5', 3),
(491, 454312233, 'US 10', 3),
(492, 454312233, 'US 10.5', 3),
(493, 454312233, 'US 11', 3),
(494, 454312233, 'US 11.5', 3),
(495, 454312233, 'US 12', 3),
(496, 955982345, 'US 7', 1),
(650, 236262610, 'US 7', 3),
(498, 955982345, 'US 8', 3),
(499, 955982345, 'US 8.5', 3),
(500, 955982345, 'US 9', 3),
(501, 955982345, 'US 9.5', 3),
(502, 955982345, 'US 10', 3),
(503, 955982345, 'US 10.5', 3),
(504, 955982345, 'US 11', 3),
(505, 955982345, 'US 11.5', 3),
(506, 955982345, 'US 12', 3),
(507, 260402624, 'US 7', 3),
(508, 260402624, 'US 7.5', 3),
(509, 260402624, 'US 8', 3),
(510, 260402624, 'US 8.5', 3),
(511, 260402624, 'US 9', 3),
(512, 260402624, 'US 9.5', 3),
(513, 260402624, 'US 10', 3),
(514, 260402624, 'US 10.5', 3),
(515, 260402624, 'US 11', 3),
(516, 260402624, 'US 11.5', 3),
(517, 260402624, 'US 12', 3),
(518, 320358821, 'US 7', 3),
(519, 320358821, 'US 7.5', 3),
(520, 320358821, 'US 8', 3),
(521, 320358821, 'US 8.5', 3),
(522, 320358821, 'US 9', 3),
(523, 320358821, 'US 9.5', 3),
(524, 320358821, 'US 10', 3),
(525, 320358821, 'US 10.5', 3),
(526, 320358821, 'US 11', 3),
(527, 320358821, 'US 11.5', 3),
(528, 320358821, 'US 12', 3),
(529, 422448398, 'US 7', 3),
(530, 422448398, 'US 7.5', 3),
(531, 422448398, 'US 8', 3),
(532, 422448398, 'US 8.5', 3),
(533, 422448398, 'US 9', 3),
(534, 422448398, 'US 9.5', 3),
(535, 422448398, 'US 10', 3),
(536, 422448398, 'US 10.5', 3),
(537, 422448398, 'US 11', 3),
(538, 422448398, 'US 11.5', 3),
(539, 422448398, 'US 12', 3),
(540, 203821907, 'US 7', 3),
(541, 203821907, 'US 7.5', 3),
(542, 203821907, 'US 8', 3),
(543, 203821907, 'US 8.5', 3),
(544, 203821907, 'US 9', 3),
(545, 203821907, 'US 9.5', 3),
(546, 203821907, 'US 10', 3),
(547, 203821907, 'US 10.5', 3),
(548, 203821907, 'US 11', 3),
(549, 203821907, 'US 11.5', 3),
(550, 203821907, 'US 12', 3),
(551, 115925985, 'US 7', 3),
(552, 115925985, 'US 7.5', 3),
(553, 115925985, 'US 8', 3),
(554, 115925985, 'US 8.5', 3),
(555, 115925985, 'US 9', 3),
(556, 115925985, 'US 9.5', 3),
(557, 115925985, 'US 10', 3),
(558, 115925985, 'US 10.5', 3),
(559, 115925985, 'US 11', 3),
(560, 115925985, 'US 11.5', 3),
(561, 115925985, 'US 12', 3),
(562, 51807240, 'US 7', 3),
(563, 51807240, 'US 7.5', 3),
(564, 51807240, 'US 8', 3),
(565, 51807240, 'US 8.5', 3),
(566, 51807240, 'US 9', 3),
(567, 51807240, 'US 9.5', 3),
(568, 51807240, 'US 10', 3),
(569, 51807240, 'US 10.5', 3),
(570, 51807240, 'US 11', 3),
(571, 51807240, 'US 11.5', 3),
(572, 51807240, 'US 12', 3),
(573, 456877743, 'US 7', 3),
(574, 456877743, 'US 7.5', 3),
(575, 456877743, 'US 8', 3),
(576, 456877743, 'US 8.5', 3),
(577, 456877743, 'US 9', 3),
(578, 456877743, 'US 9.5', 3),
(579, 456877743, 'US 10', 3),
(580, 456877743, 'US 10.5', 3),
(581, 456877743, 'US 11', 3),
(582, 456877743, 'US 11.5', 3),
(583, 456877743, 'US 12', 3),
(584, 661619842, 'US 7', 3),
(585, 661619842, 'US 7.5', 3),
(586, 661619842, 'US 8', 3),
(587, 661619842, 'US 8.5', 3),
(588, 661619842, 'US 9.5', 3),
(589, 661619842, 'US 10', 3),
(590, 661619842, 'US 10.5', 3),
(591, 661619842, 'US 11', 3),
(592, 661619842, 'US 11.5', 3),
(593, 661619842, 'US 12', 3),
(594, 661619842, 'US 9', 3),
(595, 158580830, 'US 7', 3),
(596, 158580830, 'US 7.5', 3),
(597, 158580830, 'US 8', 3),
(598, 158580830, 'US 8.5', 3),
(599, 158580830, 'US 9', 3),
(600, 158580830, 'US 9.5', 3),
(601, 158580830, 'US 10', 3),
(602, 158580830, 'US 10.5', 3),
(603, 158580830, 'US 11', 3),
(604, 158580830, 'US 11.5', 3),
(605, 158580830, 'US 12', 3),
(606, 35950620, 'US 7', 3),
(607, 35950620, 'US 7.5', 3),
(608, 35950620, 'US 8', 3),
(609, 35950620, 'US 8.5', 3),
(610, 35950620, 'US 9', 3),
(611, 35950620, 'US 9.5', 3),
(612, 35950620, 'US 10', 3),
(613, 35950620, 'US 10.5', 3),
(614, 35950620, 'US 11', 3),
(615, 35950620, 'US 11.5', 3),
(616, 35950620, 'US 12', 3),
(617, 95798472, 'US 7', 3),
(618, 95798472, 'US 7.5', 3),
(619, 95798472, 'US 8', 3),
(620, 95798472, 'US 8.5', 3),
(621, 95798472, 'US 9', 3),
(622, 95798472, 'US 9.5', 3),
(623, 95798472, 'US 10', 3),
(624, 95798472, 'US 10.5', 3),
(625, 95798472, 'US 11', 3),
(626, 95798472, 'US 11.5', 3),
(627, 95798472, 'US 12', 3),
(628, 198883440, 'US 7', 3),
(629, 198883440, 'US 7.5', 3),
(630, 198883440, 'US 8', 3),
(631, 198883440, 'US 8.5', 3),
(632, 198883440, 'US 9', 3),
(633, 198883440, 'US 9.5', 3),
(634, 198883440, 'US 10', 3),
(635, 198883440, 'US 10.5', 3),
(636, 198883440, 'US 11', 3),
(637, 198883440, 'US 11.5', 3),
(638, 198883440, 'US 12', 3),
(639, 446287022, 'US 7', 3),
(640, 446287022, 'US 7.5', 3),
(641, 446287022, 'US 8', 3),
(642, 446287022, 'US 8.5', 3),
(643, 446287022, 'US 9', 3),
(644, 446287022, 'US 9.5', 3),
(645, 446287022, 'US 10', 3),
(646, 446287022, 'US 10.5', 3),
(647, 446287022, 'US 11', 3),
(648, 446287022, 'US 11.5', 3),
(649, 446287022, 'US 12', 3),
(651, 236262610, 'US 7.5', 3),
(652, 236262610, 'US 8', 3),
(653, 236262610, 'US 8.5', 3),
(654, 236262610, 'US 9', 3),
(655, 236262610, 'US 9.5', 3),
(656, 236262610, 'US 10', 3),
(657, 236262610, 'US 10.5', 3),
(658, 236262610, 'US 11', 3),
(659, 236262610, 'US 11.5', 3),
(660, 236262610, 'US 12', 3),
(661, 557326675, 'US 7', 3),
(662, 557326675, 'US 7.5', 3),
(663, 557326675, 'US 8', 3),
(664, 557326675, 'US 8.5', 3),
(665, 557326675, 'US 9', 3),
(666, 557326675, 'US 9.5', 3),
(667, 557326675, 'US 10', 3),
(668, 557326675, 'US 10.5', 3),
(669, 557326675, 'US 11', 3),
(670, 557326675, 'US 11.5', 3),
(671, 557326675, 'US 12', 3),
(672, 624151402, 'US 7', 3),
(673, 624151402, 'US 7.5', 3),
(674, 624151402, 'US 8', 3),
(675, 624151402, 'US 8.5', 3),
(676, 624151402, 'US 9', 3),
(677, 624151402, 'US 9.5', 3),
(678, 624151402, 'US 10', 3),
(679, 624151402, 'US 10.5', 3),
(680, 624151402, 'US 11', 3),
(681, 624151402, 'US 11.5', 3),
(682, 624151402, 'US 12', 3),
(683, 285535499, 'US 7', 3),
(684, 285535499, 'US 7.5', 3),
(685, 285535499, 'US 8', 3),
(686, 285535499, 'US 8.5', 3),
(687, 285535499, 'US 9', 3),
(688, 285535499, 'US 9.5', 3),
(689, 285535499, 'US 10', 3),
(690, 285535499, 'US 10.5', 3),
(691, 285535499, 'US 11', 3),
(692, 285535499, 'US 11.5', 3),
(693, 285535499, 'US 12', 3),
(694, 293848527, 'US 7', 3),
(695, 293848527, 'US 7.5', 3),
(696, 293848527, 'US 8', 3),
(697, 293848527, 'US 8.5', 3),
(698, 293848527, 'US 9', 3),
(699, 293848527, 'US 9.5', 3),
(700, 293848527, 'US 10', 3),
(701, 293848527, 'US 10.5', 3),
(702, 293848527, 'US 11', 3),
(703, 293848527, 'US 11.5', 3),
(704, 293848527, 'US 12', 3),
(705, 929797644, 'US 7', 3),
(706, 929797644, 'US 7.5', 3),
(707, 929797644, 'US 8', 3),
(708, 929797644, 'US 8.5', 3),
(709, 929797644, 'US 9', 3),
(710, 929797644, 'US 9.5', 3),
(711, 929797644, 'US 10', 3),
(712, 929797644, 'US 10.5', 3),
(713, 929797644, 'US 11', 3),
(714, 929797644, 'US 11.5', 3),
(715, 929797644, 'US 12', 3),
(737, 944470087, 'US 12', 3),
(736, 944470087, 'US 11.5', 3),
(735, 944470087, 'US 11', 3),
(734, 944470087, 'US 10.5', 3),
(733, 944470087, 'US 10', 3),
(732, 944470087, 'US 9.5', 3),
(731, 944470087, 'US 9', 3),
(730, 944470087, 'US 8.5', 3),
(729, 944470087, 'US 8', 3),
(728, 944470087, 'US 7.5', 3),
(727, 944470087, 'US 7', 3),
(738, 473184359, 'US 7', 3),
(739, 473184359, 'US 7.5', 3),
(740, 473184359, 'US 8', 3),
(741, 473184359, 'US 8.5', 3),
(742, 473184359, 'US 9', 3),
(743, 473184359, 'US 9.5', 3),
(744, 473184359, 'US 10', 3),
(745, 473184359, 'US 10.5', 3),
(746, 473184359, 'US 11', 3),
(747, 473184359, 'US 11.5', 3),
(748, 239260727, 'US 7', 3),
(749, 239260727, 'US 7.5', 3),
(750, 239260727, 'US 8', 3),
(751, 239260727, 'US 8.5', 3),
(752, 239260727, 'US 9', 3),
(753, 239260727, 'US 9.5', 3),
(754, 239260727, 'US 10', 3),
(755, 239260727, 'US 10.5', 3),
(756, 239260727, 'US 11', 3),
(757, 239260727, 'US 11.5', 3),
(758, 239260727, 'US 12', 3);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `customerid` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `order_stat` varchar(100) NOT NULL,
  `order_date` varchar(50) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `customerid`, `amount`, `order_stat`, `order_date`, `payment_method`) VALUES
(91, 11, 6000, 'Pending', '2025-02-01 22:25:43', 'GCash');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_detail`
--

CREATE TABLE `transaction_detail` (
  `transacton_detail_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_qty` int(11) NOT NULL,
  `transaction_id` int(10) UNSIGNED DEFAULT NULL,
  `product_size` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transaction_detail`
--

INSERT INTO `transaction_detail` (`transacton_detail_id`, `product_id`, `order_qty`, `transaction_id`, `product_size`, `quantity`) VALUES
(508, 454312233, 0, 91, 'US 7', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerid`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_id`,`product_id`,`product_size`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `transaction_detail`
--
ALTER TABLE `transaction_detail`
  ADD PRIMARY KEY (`transacton_detail_id`),
  ADD UNIQUE KEY `unique_transaction_product_size` (`transaction_id`,`product_id`,`product_size`),
  ADD KEY `fk_transaction` (`transaction_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=759;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `transaction_detail`
--
ALTER TABLE `transaction_detail`
  MODIFY `transacton_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=509;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
