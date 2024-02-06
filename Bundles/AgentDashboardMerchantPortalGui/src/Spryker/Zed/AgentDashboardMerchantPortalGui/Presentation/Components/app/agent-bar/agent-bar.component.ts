import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-agent-bar',
    templateUrl: './agent-bar.component.html',
    styleUrls: ['./agent-bar.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-agent-bar' },
})
export class AgentBarComponent {}
