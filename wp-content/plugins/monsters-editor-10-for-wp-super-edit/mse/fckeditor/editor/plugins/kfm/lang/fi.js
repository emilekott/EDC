/*
 * See ../license.txt for licensing
 *
 * For further information visit:
 * 	http://kfm.verens.com/
 *
 * File Name: fi.js
 * 	Finnish language file.
 *
 * File Authors:
 * 	hnpilot@phnet.fi
 */

var kfm_lang=
{
Dir:
	"ltr", // language direction
ErrorPrefix:
	"virhe: ",
// what you see on the main page
Directories:
	"Hakemistot",
CurrentWorkingDir:
	"Nykyinen hakemisto: \"%1\"",
Logs:
	"Lokit",
FileUpload:
	"Tiedoston lisäys",
DirEmpty:
	"ei tiedostoja hakemistossa \"%1\"",

// right click menu item directory
// directory
CreateSubDir:
	"luo alihakemisto",
DeleteDir:
	"poista",
RenameDir:
	"nimeä uudelleen",

// file
DeleteFile:
	"poista",
RenameFile:
	"nimeä uudelleen",
RotateClockwise:
	"kierrä myötäpäivään",
RotateAntiClockwise:
	"kierrä vastapäivään",
ResizeImage:
	"muuta kuvan kokoa",
ChangeCaption:
	"vaihda otsikkoa",

// create a file
WhatFilenameToCreateAs:
	"Minkä niminen tiedosto luodaan?",
AskIfOverwrite:
	"Tiedosto \"%1\" on jo olemassa. Kirjoitetaanko päälle?",
NoForwardslash:
	"\nEt voi käyttää '/' tiedostonimessä",

// messages management
CreateDirMessage:
	"Luo alihakemisto hakemistolle \"%1\":",
DelDirMessage:
	"Haluatko varmasti poistaa hakemiston \"%1\"?",
DelFileMessage:
	"Haluatko varmasti poistaa tiedoston \"%1\"",
DelMultipleFilesMessage:
	"Haluatko varmasti poistaa seuraavat tiedostot?\n\n'",
DownloadFileFromMessage:
	"Lataa tiedosto mistä?",
FileSavedAsMessage:
	"Millä nimellä tiedosto tallennetaan?",

// resize file
CurrentSize:
	"Nykyinen koko: \"%1\" x \"%2\"\n",
NewWidth:
	"Uusi leveys?",
NewWidthConfirmTxt:
	"Uusi leveys: \"%1\"\n",
NewHeight:
	"Uusi korkeus?",
NewHeightConfirmTxt:
	"Uusi korkeus: \"%1\"\n\nOnko tämä oikein?",

// log messages
RenamedFile:
	"uudelleennimetään tiedosto \"%1\" tiedostoksi \"%2\".",
DirRefreshed:
	"hakemistot päivitetty.",
FilesRefreshed:
	"tiedostot päivitetty.",
NotMoreThanOneFile:
	"virhe: et voi valita kuin yhden tiedoston kerrallaan",
UnknownPanelState:
	"virhe: tuntematon tila.",
// MissingDirWrapper:
// 	"error: puuttuva tiedoston wrapperi: \"kfm_directories%1\".",
SetStylesError:
	"virhe:  \"%1\" ei voi asettaa \"%2\:ksi.",
NoPanel:
	"virhe: panelia \"%1\" ei ole.",
FileSelected:
	"Valittu tiedosto: \"%1\"",
Log_ChangeCaption:
	"muutetaan otsikko \"%1\" otsikoksi \"%2\"",
UrlNotValidLog:
	"virhe: URL:n täytyy alkaa \"http:\"",
MovingFilesTo:
	"siirretään tiedostot [\"%1\"] hakemistoon \"%2\"",

// error messages
DirectoryNameExists:
	"nimetty tiedosto on jo olemassa.",
FileNameNotAllowd:
	"virhe: tiedostonimi ei ole sallittu",
CouldNotWriteFile:
	"virhe: ei voinut kirjoittaa tiedostoon \"%1\".",
CouldNotRemoveDir:
	"ei voinut poistaa hekmistoa.\nvarmista, että se on tyhjä",
UrlNotValid:
	"virhe: URL:n täytyy alkaaa \"http:\"",
CouldNotDownloadFile:
	"virhe: ei voinut ladata tiedostoa \"%1\".",
FileTooLargeForThumb:
	"virhe: \"%1\" on liian iso pikkukuvan tekemiseksi. Korvaa kuva pienemmällä tiedostolla.",
CouldntReadDir:
	"virhe: ei voinut lukea hakemistoa",
CannotRenameFile:
	"virhe: ei voi nimetä \"%1\" nimelle \"%2\"",
FilenameAlreadyExists:
	"virhe: nimetty tiedosto on jo olemassa",

// new in 0.5
EditTextFile:
	"muokkaa tekstitiedostoa",
CloseWithoutSavingQuestion:
	"Oletko varma, ettähaluat sulkea tallentamatta?",
CloseWithoutSaving:
	"Sulje tallentamatta",
SaveThenClose:
	"Tallenna ja sulje",
SaveThenCloseQuestion:
	"Oletko varma, että haluat tallentaa muutoksesi?",

// new in 0.6
LockPanels:
	"Lukitse panelit",
UnlockPanels:
	"poista panelien lukko",
CreateEmptyFile:
	"luo tyhjä tiedosto",
DownloadFileFromUrl:
	"lataa tiedosto URL:stä",
DirectoryProperties:
	"Hakemiston ominaisuudet",
SelectAll:
	"valitse kaikki",
SelectNone:
	"poista valinta",
InvertSelection:
	"käännä valinta",
LoadingKFM:
	"lataamassa KFM:ää",
Name:
	"nimi",
FileDetails:
	"Tiedoston tiedot",
Search:
	"Etsi",
IllegalDirectoryName:
	"laiton hakemistonimi \"%1\"",
RecursiveDeleteWarning:
	"\"%1\" ei ole tyhjä\nOletko varma, että haluat poistaa sen ja kaikki sen sisältämät tiedostot?\n*VAROITUS* TÄTÄ EI VOI PERUA!",
RmdirFailed:
	"hakemiston poisto epäonnistui \"%1\"",
DirNotInDb:
	"hakemistoa ei ole tietokannassa",
ShowPanel:
	"näytä paneli \"%1\"",
ChangeCaption:
	"Vaihda otsikkoa",
NewDirectory:
	"Uusi hakemisto",
Upload:
	"Lähetä tiedosto",
NewCaptionIsThisCorrect:
	"Uusi otsikko:\n%1\n\nOnko tämä oikein?",
Close:
	"sulje",
Loading:
	"lataamssa",
AreYouSureYouWantToCloseKFM:
	"Haluatko varmasti sulkea KFM-ikkunan?",
PleaseSelectFileBeforeRename:
	"Valitse ensin tiedosto",
RenameOnlyOneFile:
	"Voit uudelleennimetä vain yhden tiedoston kerrallaan",
RenameFileToWhat:
	"vaihda nimi \"%1\" miksi?",
NoRestrictions:
	"ei rajoituksia",
Filename:
	"tiedoston nimi",
Maximise:
	"suurenna",
Minimise:
	"pienennä",
AllowedFileExtensions:
	"sallitut tiedostopäätteet",
Filesize:
	"koko",
MoveDown:
	"siirrä alas",
Mimetype:
	"MIME-tyyppi",
MoveUp:
	"siirrä ylös",
Restore:
	"palauta",
Caption:
	"otsikko",
CopyFromURL:
	"Copy from URL",
ExtractZippedFile:
	"Extract zipped file",

// new in 0.8
ViewImage:
	"view image",
ReturnThumbnailToOpener:
	"return thumbnail to opener",
AddTagsToFiles:
	"add tags to file(s)",
RemoveTagsFromFiles:
	"remove tags from file(s)",
HowWouldYouLikeToRenameTheseFiles:
	"How would you like to rename these files?\n\nexample: \"images-***.jpg\" will rename files to \"images-001.jpg\", \"images-002.jpg\", ...",
YouMustPlaceTheWildcard:
	"You must place the wildcard character * somewhere in the filename template",
YouNeedMoreThan:
	"You need more than %1 * characters to create %2 filenames",
NoFilesSelected:
	"no files selected",
Tags:
	"tags",
IfYouUseMultipleWildcards:
	"If you use multiple wildcards in the filename template, they must be grouped together",
NewCaption:
	"New Caption",
WhatMaximumSize:
	"What maximum size should be returned?",
CommaSeparated:
	"comma-separated",
WhatIsTheNewTag:
	"What is the new tag?\nFor multiple tags, separate by commas.",
WhichTagsDoYouWantToRemove:
	"Which tags do you want to remove?\nFor multiple tags, separate by commas."

,
// New in 0.9
AllFiles: "all files",
AndNMore: "...and %1 more...",
Browse: "Browse...",
ExtractAfterUpload: "extract after upload",
NotAnImageOrImageDimensionsNotReported: "error: not an image, or image dimensions not reported",
PermissionDeniedCannotDeleteFile: "permission denied: cannot delete file",
RenameTheDirectoryToWhat: "Rename the directory '%1' to what?",
RenamedDirectoryAs: "Renamed '%1' as '%2'",
TheFilenameShouldEndWithN: "The filename should end with %1",
WhatFilenameDoYouWantToUse: "What filename do you want to use?"
}
