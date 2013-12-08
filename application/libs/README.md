Put the application-specific libraries here and call them using the usual
method. The autolaod helper will load it from this folder, if it is not
available in the either system/core or system/libs folders.

Since version 2.0.38, libraries can be loaded through controllers using the
following method as well. It is introduced to make if possible to load libraries
that are inside sub-folders.

$this->load->library('LibraryName');

Libraries that are loaded using this method can be called as mentioned below.
$this->library->LibraryName;