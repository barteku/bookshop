# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.6.17)
# Database: book_store
# Generation Time: 2014-04-21 02:24:06 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table book
# ------------------------------------------------------------

DROP TABLE IF EXISTS `book`;

CREATE TABLE `book` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `authors` varchar(255) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `book` WRITE;
/*!40000 ALTER TABLE `book` DISABLE KEYS */;

INSERT INTO `book` (`id`, `title`, `authors`, `description`, `image`, `content`, `price`)
VALUES
	(1,'Bratek Book Store','bartek','description','/var/www/htdocs/book_store/assets/6bc6c6aba0936e3eb9ca1a679849337b.jpeg','/var/www/htdocs/book_store/assets/d4cd59bce6ef5396ce1ef80257c3a7b3.pdf',20),
	(2,'my 1 book','joasia','book nb2','/var/www/htdocs/book_store/assets/813a8c516130f531f58dbd3e94a93b97.jpeg','/var/www/htdocs/book_store/assets/10a2fb7c0adcb08b647592e521f00111.pdf',25),
	(3,'stefek burczy nucha','stefek author','description setfek','/var/www/htdocs/book_store/assets/5514e437dc79c9a4fc93b65e7b638000.jpeg','/var/www/htdocs/book_store/assets/9b979a1e43b4b537cc9bf0d2b45a90fa.pdf',14),
	(4,'stefek burczy nucha','joasia','description','/var/www/htdocs/book_store/assets/0ccdcbd12d1d709a733b01a53cf17cb8.jpeg','/var/www/htdocs/book_store/assets/76350c91231575fce154ae2e731f1586.pdf',14);

/*!40000 ALTER TABLE `book` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table book_review
# ------------------------------------------------------------

DROP TABLE IF EXISTS `book_review`;

CREATE TABLE `book_review` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `review` text,
  `rating` smallint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `book_review` WRITE;
/*!40000 ALTER TABLE `book_review` DISABLE KEYS */;

INSERT INTO `book_review` (`id`, `book_id`, `user`, `review`, `rating`)
VALUES
	(1,1,2,'review',3),
	(4,3,3,'nice review',5),
	(5,3,3,'extra review',NULL),
	(6,3,2,'edit review',2),
	(7,3,3,'extra review',4),
	(8,3,2,'my new review',3);

/*!40000 ALTER TABLE `book_review` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `log`;

CREATE TABLE `log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hash` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;

INSERT INTO `log` (`id`, `message`, `created`, `hash`)
VALUES
	(1,'New PP transaction {\"intent\":\"sale\",\"payer\":{\"payment_method\":\"paypal\"},\"redirect_urls\":{\"return_url\":\"http:\\/\\/localhost\\/book_store\\/purchase_activate.php\",\"cancel_url\":\"http:\\/\\/localhost\\/book_store\\/purchase_cancel.php\"},\"transactions\":[{\"amount\":{\"currency\":\"USD\",\"total\":\"14\"},\"item_list\":{\"items\":[{\"name\":\"stefek burczy nucha\",\"currency\":\"USD\",\"quantity\":1,\"price\":\"14\"}]},\"description\":\"Book store payment for stefek burczy nucha\"}]}','2014-04-21 02:40:56',NULL),
	(2,'PP transaction efter execution: {\"intent\":\"sale\",\"payer\":{\"payment_method\":\"paypal\",\"payer_info\":{\"shipping_address\":[]}},\"redirect_urls\":{\"return_url\":\"http:\\/\\/localhost\\/book_store\\/purchase_activate.php\",\"cancel_url\":\"http:\\/\\/localhost\\/book_store\\/purchase_cancel.php\"},\"transactions\":[{\"amount\":{\"total\":\"14.00\",\"currency\":\"USD\",\"details\":{\"subtotal\":\"14.00\"}},\"description\":\"Book store payment for stefek burczy nucha\",\"item_list\":{\"items\":[{\"name\":\"stefek burczy nucha\",\"price\":\"14.00\",\"currency\":\"USD\",\"quantity\":\"1\"}]}}],\"id\":\"PAY-77V8410946410864HKNKHOJY\",\"create_time\":\"2014-04-21T01:40:55Z\",\"update_time\":\"2014-04-21T01:40:55Z\",\"state\":\"created\",\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v1\\/payments\\/payment\\/PAY-77V8410946410864HKNKHOJY\",\"rel\":\"self\",\"method\":\"GET\"},{\"href\":\"https:\\/\\/www.sandbox.paypal.com\\/cgi-bin\\/webscr?cmd=_express-checkout&token=EC-6EU65807SC626594V\",\"rel\":\"approval_url\",\"method\":\"REDIRECT\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v1\\/payments\\/payment\\/PAY-77V8410946410864HKNKHOJY\\/execute\",\"rel\":\"execute\",\"method\":\"POST\"}]}','2014-04-21 02:40:59',NULL),
	(3,'PP payment before execution: {\"id\":\"PAY-77V8410946410864HKNKHOJY\",\"create_time\":\"2014-04-21T01:40:55Z\",\"update_time\":\"2014-04-21T01:40:55Z\",\"state\":\"created\",\"intent\":\"sale\",\"payer\":{\"payment_method\":\"paypal\",\"payer_info\":{\"shipping_address\":[]}},\"transactions\":[{\"amount\":{\"total\":\"14.00\",\"currency\":\"USD\",\"details\":{\"subtotal\":\"14.00\"}},\"description\":\"Book store payment for stefek burczy nucha\",\"item_list\":{\"items\":[{\"name\":\"stefek burczy nucha\",\"price\":\"14.00\",\"currency\":\"USD\",\"quantity\":\"1\"}]}}],\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v1\\/payments\\/payment\\/PAY-77V8410946410864HKNKHOJY\",\"rel\":\"self\",\"method\":\"GET\"},{\"href\":\"https:\\/\\/www.sandbox.paypal.com\\/cgi-bin\\/webscr?cmd=_express-checkout&token=EC-6EU65807SC626594V\",\"rel\":\"approval_url\",\"method\":\"REDIRECT\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v1\\/payments\\/payment\\/PAY-77V8410946410864HKNKHOJY\\/execute\",\"rel\":\"execute\",\"method\":\"POST\"}]}','2014-04-21 02:41:23',NULL),
	(4,'PP payment after execution: {\"id\":\"PAY-77V8410946410864HKNKHOJY\",\"create_time\":\"2014-04-21T01:40:55Z\",\"update_time\":\"2014-04-21T01:40:55Z\",\"state\":\"created\",\"intent\":\"sale\",\"payer\":{\"payment_method\":\"paypal\",\"payer_info\":{\"shipping_address\":[]}},\"transactions\":[{\"amount\":{\"total\":\"14.00\",\"currency\":\"USD\",\"details\":{\"subtotal\":\"14.00\"}},\"description\":\"Book store payment for stefek burczy nucha\",\"item_list\":{\"items\":[{\"name\":\"stefek burczy nucha\",\"price\":\"14.00\",\"currency\":\"USD\",\"quantity\":\"1\"}]}}],\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v1\\/payments\\/payment\\/PAY-77V8410946410864HKNKHOJY\",\"rel\":\"self\",\"method\":\"GET\"},{\"href\":\"https:\\/\\/www.sandbox.paypal.com\\/cgi-bin\\/webscr?cmd=_express-checkout&token=EC-6EU65807SC626594V\",\"rel\":\"approval_url\",\"method\":\"REDIRECT\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v1\\/payments\\/payment\\/PAY-77V8410946410864HKNKHOJY\\/execute\",\"rel\":\"execute\",\"method\":\"POST\"}]}','2014-04-21 02:41:26',NULL);

/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table purchase
# ------------------------------------------------------------

DROP TABLE IF EXISTS `purchase`;

CREATE TABLE `purchase` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL,
  `paymentId` varchar(255) DEFAULT NULL,
  `PayerID` varchar(255) DEFAULT NULL,
  `downloads` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `purchase` WRITE;
/*!40000 ALTER TABLE `purchase` DISABLE KEYS */;

INSERT INTO `purchase` (`id`, `book_id`, `user`, `token`, `status`, `paymentId`, `PayerID`, `downloads`)
VALUES
	(1,1,1,'EC-8NT42494R6414364D','new','PAY-0FS38590AV9168413KNKFGIA',NULL,0),
	(2,1,1,'EC-2AR931220N9907547','new','PAY-0KR5773488762213PKNKFG6I',NULL,0),
	(3,1,1,'EC-4AR267720J778272X','new','PAY-0BP79797KL2521435KNKFHFY',NULL,0),
	(4,1,1,'EC-0C457689CE839760B','new','PAY-4MA53491S47781148KNKFQ4Q',NULL,0),
	(5,1,1,'EC-89342544BA742691R','new','PAY-37X5140373062345KKNKF2WA',NULL,0),
	(6,2,1,'EC-89B59897YC7818404','new','PAY-6YM03213KX091763KKNKF4UI',NULL,0),
	(7,2,1,'EC-13638824UF6135026','new','PAY-8T468848UR335220MKNKF5XQ',NULL,0),
	(8,1,1,'EC-9GN86732GU087480B','new','PAY-7N9612307J0396324KNKGCUY',NULL,0),
	(9,1,1,'EC-1P574828ST924453U','paid','PAY-99H465609F215944CKNKGDIA','L8U9HZK3T68B4',0),
	(10,1,1,'EC-5CA05118JE979734W','new','PAY-4NH24842H9418292LKNKGFEI',NULL,0),
	(11,1,1,'EC-1WW02624HX061313R','new','PAY-92552814JX808981WKNKGRCY',NULL,0),
	(12,1,2,'EC-10U87907GM2084601','paid','PAY-35675627TG404282AKNKGREA','L8U9HZK3T68B4',2),
	(13,3,3,'EC-6EU65807SC626594V','paid','PAY-77V8410946410864HKNKHOJY','L8U9HZK3T68B4',0);

/*!40000 ALTER TABLE `purchase` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `username`, `email`, `password`, `type`)
VALUES
	(1,'bartek','bartek@gmail.com','098f6bcd4621d373cade4e832627b4f6','admin'),
	(2,'test','email@email.com','098f6bcd4621d373cade4e832627b4f6','user'),
	(3,'joasia','emailjoasia@email.com','098f6bcd4621d373cade4e832627b4f6','user');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
