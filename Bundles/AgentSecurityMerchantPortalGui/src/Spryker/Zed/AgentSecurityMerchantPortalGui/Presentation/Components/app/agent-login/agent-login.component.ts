import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-agent-login',
    templateUrl: './agent-login.component.html',
    styleUrls: ['./agent-login.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class AgentLoginComponent {}
