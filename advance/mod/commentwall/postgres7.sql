CREATE TABLE `prefix_commentwall` (
  `ident` SERIAL PRIMARY KEY,
  
  `wallowner` int(11) NOT NULL,
  
  `comment_owner` int(11) NOT NULL,
  `content` text NOT NULL,
  
  `posted` int(11) NOT NULL,
  
  PRIMARY KEY  (`ident`)
);
