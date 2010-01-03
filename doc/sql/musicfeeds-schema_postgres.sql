--CREATE FUNCTION update_datemodified_column() RETURNS TRIGGER AS '
--  begin 
--    new.date_modified := now(); 
--    RETURN new; 
--  end;
--' LANGUAGE plpgsql; 

CREATE TABLE musicfeeds_usercomments (
  id            SERIAL PRIMARY KEY,
  date_modified TIMESTAMP DEFAULT now(),
  
  date          TIMESTAMP NOT NULL DEFAULT now(),
  url           TEXT,
  author_name   TEXT,
  author_email  TEXT,
  comments      TEXT
);
CREATE TRIGGER musicfeeds_usercomments_update_datemodified BEFORE UPDATE ON musicfeeds_usercomments FOR EACH ROW EXECUTE PROCEDURE update_datemodified_column();


CREATE TABLE musicfeeds_lastfm_usercharts (
  id            SERIAL PRIMARY KEY,
  date_modified TIMESTAMP DEFAULT now(),

  date          TIMESTAMP NOT NULL DEFAULT now(),
  name          TEXT NOT NULL,
  chart_type    TEXT NOT NULL
);
CREATE TRIGGER musicfeeds_lastfm_usercharts_update_datemodified BEFORE UPDATE ON musicfeeds_lastfm_usercharts FOR EACH ROW EXECUTE PROCEDURE update_datemodified_column();

CREATE TABLE musicfeeds_lastfm_userchart_artists (
  id            SERIAL PRIMARY KEY,
  date_modified TIMESTAMP DEFAULT now(),

  lastfm_user_id INTEGER NOT NULL,
  name          TEXT NOT NULL,
  rank          INTEGER,
  playcount     INTEGER,
  mbid          TEXT,
  url           TEXT
);
CREATE TRIGGER musicfeeds_lastfm_userchart_artists_update_datemodified BEFORE UPDATE ON musicfeeds_lastfm_userchart_artists FOR EACH ROW EXECUTE PROCEDURE update_datemodified_column();
