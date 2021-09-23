create database if not exists ScienceFair;
use ScienceFair;

drop table if exists Administrator;

drop table if exists Judging;
drop table if exists Session;
drop table if exists Judge;
drop table if exists Degree;

drop table if exists Student;
drop table if exists Grade;
drop table if exists School;
drop table if exists County;

drop table if exists Project;
drop table if exists Booth;
drop table if exists Category;

source Category.sql;
show warnings;

source Booth.sql;
show warnings;

source Project.sql;
show warnings;

source County.sql;
show warnings;

source School.sql;
show warnings;

source Grade.sql;
show warnings;

source Student.sql;
show warnings;

source Degree.sql;
show warnings;

source Judge.sql;
show warnings;

source Judging.sql;
show warnings;

source AverageRanking.sql;
show warnings;

source Administrator.sql;
show warnings;

