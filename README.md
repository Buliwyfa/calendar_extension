## Description
Calendar extension for integration of external calendars with [iCalendar-format](https://en.wikipedia.org/wiki/ICalendar).

This module has **only been tested** in **Humhub version 1.2.3** with the **calendar module version 0.6.3**!

## Installation
First you have to activate the original calendar module for humhub.
The calendar_extension module only works if it is activated!

By default, this module prevents the calendar events from being posted to the stream. You can change this by editing the settings in the admin area:
> administration->module->calendar extension->configuration


If you want to add external calendars, go to a specific space (or your own profile), activate the calendar_extension module in the space settings (or profile settings) and start the configuration of the module here.

The extension module uses the hourly cron event to check for changes. But you can also manually sync the calendar by going to:

> space/profile -> modules -> external calendar -> configurate -> choose your calendar (show) -> click on sync-button


**If there is an error, something went wrong with your sync.**


*Hints*:
- Events will only be changed, if the last_modified field or the uid field of the external calendar changes.
- When you try to add an external calendar, the module first checks whether the URL you added is correct and can be converted to an iCal file.
**Some iCal-Url's start with a** `webcal://`. All you have to do is to **change this to** `http://` or `https://`
For example:
```
Original: webcal://calendar.google.com/calendar/ical/....
Change to: https://calendar.google.com/calendar/ical/...
```
## Informations
This is the first module I've written and I hope it works for you. I have oriented myself on the original calendar module code.
I also hope that I can find a solution for the ["upcoming-events"-issue](https://github.com/staxDB/calendar_extenstion/issues/1), but I haven't found a solution yet. Maybe one of you can do that.


__Module website:__ <https://github.com/staxDB/calendar_extension.git>    
__Author:__ David Born    

## Changelog

<https://github.com/staxDB/calendar_extension/commits/master>

## Bugtracker

<https://github.com/staxDB/calendar_extension/issues>

## ToDos
- fix bug with calendar-widget "upcoming events"


This Module uses the Calendar UI Interface in v0.6 - [see dokumentation](https://github.com/humhub/humhub-modules-calendar/blob/master/docs/interface.md)
