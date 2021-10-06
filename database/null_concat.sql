use ScienceFair;

/*
	null-safe wrapper of concat().
*/
drop function if exists null_concat;

delimiter //

create function null_concat
