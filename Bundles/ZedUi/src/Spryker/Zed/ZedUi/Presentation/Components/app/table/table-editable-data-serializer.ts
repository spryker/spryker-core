import { Injectable } from '@angular/core';
import { TableDataRow } from '@spryker/table';
import { DataSerializer } from '@spryker/data-serializer';

@Injectable()
export class TableEditableDataSerializer implements DataSerializer<TableDataRow> {
  serialize(data: TableDataRow): string {
    return JSON.stringify(data);
  }
}
