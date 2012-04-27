/*
 * Licensed under the terms of the GNU Lesser General Public License:
 * 	http:
 *
 * For further information visit:
 * 	http:
 *
 * File Name: es.js
 * 	Spanish (latin-american) language file.
 *
 * File Authors:
 *  arathael@gmail.com
 *
 * Last Updated:
 *  6/08/2006
 */

var kfm_lang=
{
Dir:
	"ltr", 
ErrorPrefix:
	"error: ",

Directories:
	"Directorios",
CurrentWorkingDir:
	"Directorio actual: \"%1\"",
Logs:
	"Registros",
FileUpload:
	"Subir archivo",
DirEmpty:
	"no se encontraron archivos en \"%1\"",

CreateSubDir:
	"crear sub-directorio",
DeleteDir:
	"eliminar",
RenameDir:
	"renombrar",

DeleteFile:
	"eliminar",
RenameFile:
	"renombrar",
RotateClockwise:
	"girar en sentido a las manecillas del reloj",
RotateAntiClockwise:
	"girar en sentido inverso a las manecillas del reloj",
ResizeImage:
	"redimensionar imagen",
ChangeCaption:
	"cambiar etiqueta",

FileName:
	"¿Nombre del nuevo archivo?",
AskIfOverwrite:
	"El archivo \"%1\" ya existe. ¿Sobreescribir?",
NoForwardslash:
	"\nNo puedes utilizar '/' en el nombre del archivo",

CreateDirMessage:
	"Crear un sub-directorio en \"%1\":",
DelDirMessage:
	"¿Estás seguro de eliminar el directorio \"%1\"?",
DelFileMessage:
	"¿Estás seguro de eliminar el archivo \"%1\"",
DelMultipleFilesMessage:
	"¿Estás seguro de eliminar estos archivos?\n\n'",
DownloadFileFromMessage:
	"¿De donde se descargará el archivo?",
FileSavedAsMessage:
	"Con que nombre se deberá guardar el archivo?",

CurrentSize:
	"Dimensiones actuales: \"%1\" x \"%2\"\n",
NewWidth:
	"¿Nuevo ancho?",
NewWidthConfirmTxt:
	"Nuevo ancho establecido: \"%1\"\n",
NewHeight:
	"¿Nueva altura?",
NewHeightConfirmTxt:
	"Nueva altura establecida: \"%1\"\n\n¿Es esto correcto?",

RenamedFile:
	"Archivo \"%1\" renombrado a \"%2\".",
DirRefreshed:
	"directorios actualizados.",
FilesRefreshed:
	"archivos actualizados.",
NotMoreThanOneFile:
	"error: no puedes seleccionar más de un archivo a la vez.",
UnknownPanelState:
	"error: estado de panel desconocido.",

SetStylesError:
	"error: no se puede renombrar \"%1\" a \"%2\.",
NoPanel:
	"error: el panel \"%1\" no existe.",
FileSelected:
	"archivo seleccionado: \"%1\"",
Log_ChangeCaption:
	"cambiando etiqueta de \"%1\" a \"%2\"",
UrlNotValidLog:
	"error: los URLs deben de comenzar con \"http:\"",
MovingFilesTo:
	"moviendo archivos [\"%1\"] a \"%2\"",

DirectoryNameExists:
	"ya existe un directorio con ese nombre.",
FileNameNotAllowd:
	"error: nombre de archivo no permitido",
CouldNotWriteFile:
	"error: no se pudo escribir el archivo \"%1\".",
CouldNotRemoveDir:
	"no se pudo eliminar el directorio.\nporfavor asegúrate de que está vacío",
UrlNotValid:
	"error: los URLs debe de comenzar con \"http:\"",
CouldNotDownloadFile:
	"error: no se pudo descargar el archivo \"%1\".",
FileTooLargeForThumb:
	"error: \"%1\" es muy grande para generar una miniatura. Porfavor reemplaza el archivo con una versión más pequeña.",
CouldntReadDir:
	"error: no se pudo leer el directorio",
CannotRenameFile:
	"error: no se pudo renombrar \"%1\" a \"%2\"",
FilenameAlreadyExists:
	"error: ya existe un archivo con ese nombre",

// new in 0.5
EditTextFile:
	"editar archivo de texto",
CloseWithoutSavingQuestion:
	"¿Salir sin guardar los cambios?",
CloseWithoutSaving:
	"Cerrar sin guardar los cambios",
SaveThenClose:
	"Guardar y cerrar",
SaveThenCloseQuestion:
	"¿Guardar los cambios?",

// new in 0.6
LockPanels:
	"bloquear paneles",
UnlockPanels:
	"desbloquear paneles",
CreateEmptyFile:
	"crear archivo vacío",
DownloadFileFromUrl:
	"descargar archivo desde ubicación",
DirectoryProperties:
	"Propiedades de carpeta",
SelectAll:
	"seleccionar todo",
SelectNone:
	"deseleccionar",
InvertSelection:
	"invertir selección",
LoadingKFM:
	"cargando KFM",
Name:
	"nombre",
FileDetails:
	"File Details",
Search:
	"Search",
IllegalDirectoryName:
	"illegal directory name \"%1\"",
RecursiveDeleteWarning:
	"\"%1\" is not empty\nAre you sure you want to delete it and all its contents?\n*WARNING* THIS IS NOT REVERSIBLE",
RmdirFailed:
	"failed to delete directory \"%1\"",
DirNotInDb:
	"directory not in database",
ShowPanel:
	"show panel \"%1\"",
ChangeCaption:
	"Change Caption",
NewDirectory:
	"New Directory",
Upload:
	"Upload",
NewCaptionIsThisCorrect:
	"New Caption:\n%1\n\nIs this correct?",
Close:
	"close",
Loading:
	"loading",
AreYouSureYouWantToCloseKFM:
	"Are you sure you want to close the KFM window?",
PleaseSelectFileBeforeRename:
	"Please select a file before you try to rename it",
RenameOnlyOneFile:
	"You can only rename one file at a time",
RenameFileToWhat:
	"Rename the file \"%1\" to what?",
NoRestrictions:
	"no restrictions",
Filename:
	"filename",
Maximise:
	"maximise",
Minimise:
	"minimise",
AllowedFileExtensions:
	"allowed file extensions",
Filesize:
	"filesize",
MoveDown:
	"move down",
Mimetype:
	"mimetype",
MoveUp:
	"move up",
Restore:
	"restore",
Caption:
	"caption",
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
