import { NgModule } from '@angular/core';
import { DatasourceModule } from '@spryker/datasource';
import { DatasourceHttpModule, DatasourceHttpService } from '@spryker/datasource.http';
import { DatasourceInlineModule, DatasourceInlineService } from '@spryker/datasource.inline';
import { TableDatasourceInlineModule, TableDatasourceInlineService } from '@spryker/datasource.inline.table';
import { TableDatasourceDependableService } from '@spryker/table.feature.editable';

@NgModule({
    imports: [
        DatasourceModule.withDatasources({
            http: DatasourceHttpService,
            inline: DatasourceInlineService,
            'inline.table': TableDatasourceInlineService,
            dependable: TableDatasourceDependableService,
        }),
        DatasourceHttpModule,
        DatasourceInlineModule,
        TableDatasourceInlineModule.withConfig(),
    ],
})
export class DefaultDatasourcesConfigModule {}
