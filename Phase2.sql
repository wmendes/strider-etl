SELECT 
  based / total * 100 AS percentage 
FROM 
  (
    SELECT 
      Count(DISTINCT movie_title) AS total 
    FROM 
      streams s
  ) q1 
  INNER JOIN (
    SELECT 
      Count(DISTINCT movie_title) AS based 
    FROM 
      streams s1 
      INNER JOIN reviews r ON s1.movie_title = r.movie
  ) q2 


###########################################################################


SELECT 
  * 
FROM 
  streams s 
WHERE 
  movie_title = 'Unforgiven' 
  AND (
    (
      s.start_at BETWEEN TIMESTAMP(
        MAKEDATE(
          YEAR(s.start_at), 
          1
        ) + INTERVAL 11 MONTH + INTERVAL 24 DAY + INTERVAL 7 HOUR
      ) 
      AND TIMESTAMP(
        MAKEDATE(
          YEAR(s.start_at), 
          1
        ) + INTERVAL 11 MONTH + INTERVAL 24 DAY + INTERVAL 12 HOUR
      ) 
      OR s.start_at BETWEEN TIMESTAMP(
        MAKEDATE(
          YEAR(s.end_at), 
          1
        ) + INTERVAL 11 MONTH + INTERVAL 24 DAY + INTERVAL 7 HOUR
      ) 
      AND TIMESTAMP(
        MAKEDATE(
          YEAR(s.end_at), 
          1
        ) + INTERVAL 11 MONTH + INTERVAL 24 DAY + INTERVAL 12 HOUR
      ) 
      OR s.end_at BETWEEN TIMESTAMP(
        MAKEDATE(
          YEAR(s.start_at), 
          1
        ) + INTERVAL 11 MONTH + INTERVAL 24 DAY + INTERVAL 7 HOUR
      ) 
      AND TIMESTAMP(
        MAKEDATE(
          YEAR(s.start_at), 
          1
        ) + INTERVAL 11 MONTH + INTERVAL 24 DAY + INTERVAL 12 HOUR
      ) 
      OR s.end_at BETWEEN TIMESTAMP(
        MAKEDATE(
          YEAR(s.end_at), 
          1
        ) + INTERVAL 11 MONTH + INTERVAL 24 DAY + INTERVAL 7 HOUR
      ) 
      AND TIMESTAMP(
        MAKEDATE(
          YEAR(s.end_at), 
          1
        ) + INTERVAL 11 MONTH + INTERVAL 24 DAY + INTERVAL 12 HOUR
      )
    )
  ) 


#####################################################################################



select 
  count(
    DISTINCT(s.movie_title)
  ) 
from 
  streams s 
  inner join reviews r on r.movie = s.movie_title 
  inner join books b on b.name = r.book 
  inner join authors a on a.name = b.author 
where 
  a.nationality = 'Singaporeans' 


#####################################################################################



select 
  sum(
    TIMESTAMPDIFF(SECOND, start_at, end_at)
  ) / count(id) 
from 
  streams s 

#####################################################################################



select 
  (
    sum(size_mb) / count(id)
  ) / 1024 
from 
  streams s 


################################################################################


select 
  COUNT(
    distinct(u.id)
  ) 
from 
  users u 
  inner join streams s on s.user_email = u.email 
  inner join movies m on m.title = s.movie_title 
where 
  s.size_mb >= (m.size_mb / 2) 
  AND (
    (
      s.start_at BETWEEN DATE_ADD(
        LAST_DAY(s.start_at), 
        INTERVAL -1 WEEK
      ) 
      AND LAST_DAY(s.start_at) 
      OR s.start_at BETWEEN DATE_ADD(
        LAST_DAY(s.end_at), 
        INTERVAL -1 WEEK
      ) 
      AND LAST_DAY(s.end_at) 
      OR s.end_at BETWEEN DATE_ADD(
        LAST_DAY(s.start_at), 
        INTERVAL -1 WEEK
      ) 
      AND LAST_DAY(s.end_at) 
      OR s.end_at BETWEEN DATE_ADD(
        LAST_DAY(s.end_at), 
        INTERVAL -1 WEEK
      ) 
      AND LAST_DAY(s.end_at)
    )
  )

  ####################################################################################################
