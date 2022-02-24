# Cascading Comments
An index of Laravel's cascading comments.

Visit the site at [cascading-comments.sjorso.com](https://cascading-comments.sjorso.com).

## About
This is a cascading comment:

https://github.com/SjorsO/cascading-comments/blob/2002a252216907239460eafb31ca1666a10bc393/artisan#L26-L30

Each line is three characters shorter than the previous line. The last line is two characters shorter and ends with punctuation. If a comment follows these rules exactly, we consider it a "perfect" cascading comment.

These types of comments are everywhere in Laravel's source code.
The skeleton, framework, and most first-party packages have them.
This project finds and indexes all of these comments per Laravel release.

## Technical overview
The cron runs three jobs:
- [PollReleasesJob](https://github.com/SjorsO/cascading-comments/blob/master/app/Jobs/PollReleasesJob.php) - Uses the GitHub API to find new release for repositories we're watching
- [DownloadReleaseJob](https://github.com/SjorsO/cascading-comments/blob/master/app/Jobs/DownloadReleaseJob.php) - Downloads the zip of new releases
- [ProcessReleaseJob](https://github.com/SjorsO/cascading-comments/blob/master/app/Jobs/ProcessReleaseJob.php) - Finds cascading comments in a release and stores them in the database

These files are interesting:
- [GithubApi](https://github.com/SjorsO/cascading-comments/blob/master/app/Lcc/Github/GithubApi.php)  - Makes GraphQL calls to get all releases of a repository
- [ReleaseFile](https://github.com/SjorsO/cascading-comments/blob/master/app/Lcc/ReleaseFile.php) - Finds all cascading comments in a file
- [CascadingComment](https://github.com/SjorsO/cascading-comments/blob/master/app/Lcc/CascadingComment.php) - Detects if a cascading comment is perfect
