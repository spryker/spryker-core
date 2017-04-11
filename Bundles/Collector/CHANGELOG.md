# Collector Changelog

[Collector Changelog](https://github.com/spryker/Collector/releases)
Release v1.0.0
 - Added export of all locales configured in store
 - Clear separation between exporting / collecting data (AbstractExporter and AbstractCollector)
 - Clear separation between processing / collecting data (Collector Queries)
 - Added possibility to use raw SQL queries along side Propel generated queries (AbstractPropelCollectorQuery and AbstractPdoCollectorQuery)
 - All queries are located in Persistence layer 
 - Renamed KeyValue into Storage (Reader, Writer) 
 - Much better performance with bulk database operations (PdoBatchIterator / PropelBatchIterator)
 - Better summary, verbosity and feedback during export (ProgressBar and ProgressBarBuilder)

