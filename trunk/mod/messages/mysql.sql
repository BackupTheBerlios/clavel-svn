alter table prefix_messages add column  hidden_from enum('1','0') NOT NULL default 0;
alter table prefix_messages add column  hidden_to enum('1','0') NOT NULL default 0;
