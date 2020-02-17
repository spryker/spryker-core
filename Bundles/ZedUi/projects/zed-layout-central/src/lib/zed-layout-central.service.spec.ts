import { TestBed } from '@angular/core/testing';

import { ZedLayoutCentralService } from './zed-layout-central.service';

describe('ZedLayoutCentralService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ZedLayoutCentralService = TestBed.get(ZedLayoutCentralService);
    expect(service).toBeTruthy();
  });
});
