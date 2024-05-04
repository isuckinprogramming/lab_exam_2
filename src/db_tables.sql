
create database if not exists `lab_exam_2_lim`;

use lab_exam_2_lim;

create table if not exists `user` (
  id int primary key auto_increment,
  name text,
  picture text
);