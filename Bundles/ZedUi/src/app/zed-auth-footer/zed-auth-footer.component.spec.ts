import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ZedAuthFooterComponent } from './zed-auth-footer.component';

describe('ZedAuthFooterComponent', () => {
  let component: ZedAuthFooterComponent;
  let fixture: ComponentFixture<ZedAuthFooterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ZedAuthFooterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ZedAuthFooterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
