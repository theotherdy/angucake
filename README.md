# angucake

Attempt to recreate [Jan Ranostaj's](https://medium.com/@ranostaj) post on Auth in CakePHP 3 and AngularJS:

https://medium.com/@ranostaj/cakephp-3-login-with-angularjs-7f89bab4c20

With this DB:

```
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'ausername', 'apassword');
--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
```

