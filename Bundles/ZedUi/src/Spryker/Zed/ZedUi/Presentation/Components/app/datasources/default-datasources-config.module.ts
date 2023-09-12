import { NgModule } from '@angular/core';
import { DatasourceModule } from '@spryker/datasource';
import { DatasourceDependableModule, DatasourceDependableService } from '@spryker/datasource.dependable';
import { DatasourceHttpModule, DatasourceHttpService } from '@spryker/datasource.http';
import { DatasourceInlineModule, DatasourceInlineService } from '@spryker/datasource.inline';
import { TableDatasourceInlineModule, TableDatasourceInlineService } from '@spryker/datasource.inline.table';
import { DatasourceTriggerModule, DatasourceTriggerService } from '@spryker/datasource.trigger';
import { ChangeDatasourceTriggerService } from '@spryker/datasource.trigger.change';
import { InputDatasourceTriggerService } from '@spryker/datasource.trigger.input';
import { TableDatasourceDependableService } from '@spryker/table.feature.editable';

declare module '@spryker/datasource' {
    interface DatasourceRegistry {
        http: DatasourceHttpService;
        inline: DatasourceInlineService;
        'inline.table': TableDatasourceInlineService;
        dependable: TableDatasourceDependableService;
        'dependable-element': DatasourceDependableService;
        trigger: DatasourceTriggerService;
    }
}

@NgModule({
    imports: [
        DatasourceModule.withDatasources({
            http: DatasourceHttpService,
            inline: DatasourceInlineService,
            'inline.table': TableDatasourceInlineService,
            dependable: TableDatasourceDependableService,
            'dependable-element': DatasourceDependableService,
            trigger: DatasourceTriggerService,
        }),
        DatasourceHttpModule,
        DatasourceInlineModule,
        DatasourceDependableModule,
        DatasourceTriggerModule.withEvents({
            change: ChangeDatasourceTriggerService,
            input: InputDatasourceTriggerService,
        }),
        TableDatasourceInlineModule.withConfig(),
    ],
})
export class DefaultDatasourcesConfigModule {}
